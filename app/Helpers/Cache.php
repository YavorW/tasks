<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Кешира eloquent заявка
 *
 * @param Builder $eloquent
 * @param array $tags
 * @param string $finish по подразбиране е get. За странициране - ['paginate', $page, $per_page]
 * @param integer|null $seconds При null се запомня за постоянно
 * @return null|object връща резултата от заявката
 */
function cacheQuery(Builder $eloquent, $finish = 'get', $tags = [], $seconds = null)
{
    $sql = sqlDump($eloquent);
    $per_page = null;
    $page = null;
    if (is_array($finish)) {
        $page = $finish[1];
        $per_page = (isset($finish[2])) ? $finish[2] : paginate;
        $finish = $finish[0];
        $sql .= "paginate $page $per_page";
    }

    $cache_key = md5($sql);

    // ако е в режим на разработване - да не се кешира
    // TODO да се провери настройката в .env файла
    if (config('app.debug')) {
        if ($finish == 'paginate') {
            $eloquent = $eloquent->paginate($per_page, ['*'], 'page', $page);
            return $eloquent;
        }
        return $eloquent->$finish();
    }

    // тагове
    if (!empty($tags)) {
        $cache_tags = Cache::pull('_cache_tags', []);
        // ако има тагове, то ключовете им биват записвани в списъка
        foreach ($tags as $tag) {
            $cache_tags[$tag][$cache_key] = true;
        }
        Cache::rememberForever('_cache_tags', function () use ($cache_tags) {
            return $cache_tags;
        });
    }

    $eloquent = Cache::remember($cache_key, $seconds, function () use ($eloquent, $finish, $page, $per_page) {
        if ($finish == 'paginate') {
            return $eloquent->paginate($per_page, ['*'], 'page', $page);
        }
        return $eloquent->$finish();
    });
    // ако се ползва от различни места paginate кешира и текущият път на викане
    // => линковете, които се генерират за страницирането се оказват грешни
    if ($finish == 'paginate') {
        $eloquent = $eloquent->withPath(request()->getPathInfo());
    }
    return $eloquent;
}

/**
 *
 * @param Builder $eloquent
 * @return string sql
 */
function sqlDump(Builder $eloquent)
{
    $sql = $eloquent->toSql();
    $bindings = $eloquent->getBindings();
    foreach ($bindings as $replace) {
        $pos = strpos($sql, '?');
        if ($pos !== false) {
            $sql = substr_replace($sql, "'$replace'", $pos, 1);
        }
    }
    return $sql;
}

/**
 * Изтрива заявка от кеша
 *
 * @param string $key
 * @return bool
 */
function cacheForget(Builder $eloquent)
{
    
    // ако е в режим на разработване -няма кеш
    if (config('app.debug')) return;
    
    $key = sqlDump($eloquent);
    return Cache::forget(md5($key));
}

function cacheForgetTags($tags = [])
{
    
    // ако е в режим на разработване - няма кеш
    if (config('app.debug')) return;
    
    // взима информацията информацията за кеша
    $cache_tags = Cache::pull('_cache_tags', []);
    if(empty($cache_tags)) return;

    // когато се подават едновременно няколко неща за триене
    // примерно $tags = ['users','inventory']
    if (is_array($tags)) {
        foreach ($tags as $tag) {
            if (key_exists($tag, $cache_tags)) {
                foreach ($cache_tags[$tag] as $key => $v) {
                    Cache::forget($key);
                }
                unset($cache_tags[$tag]);
            }
        }
    } else {
        // само един ресурс; примерно $tags = 'users'
        if (isset($cache_tags[$tags])) {
            foreach ($cache_tags[$tags] as $key => $v) {
                Cache::forget($key);
            }
            unset($cache_tags[$tags]);
        }
    }
    // записва се кеша отново
    Cache::rememberForever('_cache_tags', function () use ($cache_tags) {
        return $cache_tags;
    });
}

define('cache_seperator', '||');

/**
 * записва заявка в кеш менижъра, [спрямо група и параметри] като ключ и за колко време
 *
 */
function cacheApi($group = null, $params = null, $seconds = null, $callback = null)
{
    $cache_manager = Cache::get('_cache_manager', []);
    $cache_manager[$group][$params] = time() + $seconds; // кога изтича записа

    Cache::forever('_cache_manager', $cache_manager);

    return Cache::remember($group . cache_seperator . $params, $seconds, $callback);
}

function getCachedApi($group = null, $params = null)
{
    $cache_manager = Cache::get('_cache_manager', []);
    // почистване на регистъра от изтекли записи
    foreach ($cache_manager as $cache_group => $values) {
        // запис без параметри се записва като ""
        foreach ($values as $cache_params => $time) {
            if ($time < time()) { // изтеклите записи се почистват
                unset($cache_manager[$cache_group][$cache_params]); // изтрива параметъра
                // кеша не би трябвало да съществува, затова няма нужда от допълнително триене
            }
        }
        if (empty($cache_manager[$cache_group])) {
            //когато групата/записите без параметри остане без валидни записи, тя е за триене
            unset($cache_manager[$cache_group]);
        }
    }
    if ($group) {
        if ($params) {
            return isset($cache_manager[$group][$params]) ? $cache_manager[$group][$params] : null;
        }
        return isset($cache_manager[$group]) ? $cache_manager[$group] : null;
    }
    // обновява се информацията за кеш менижъра
    Cache::forever('_cache_manager', $cache_manager);
    return $cache_manager ?? [];

}
/** Изтриване на запис от кеша и кеш менижъра */
function cachedApiForget($group = null, $params = null)
{
    $cache_manager = Cache::get('_cache_manager', []);
    if ($params) {
        $cache_key = $group . cache_seperator . $params;
        Cache::forget($cache_key);
        unset($cache_manager[$group][$params]);
    } else {
        // изтриване на цялата група
        foreach ($cache_manager[$group] as $params => $time) {
            // всеки един кеш по оделно
            $cache_key = $group . cache_seperator . $params;
            Cache::forget($cache_key);
            unset($cache_manager[$group][$params]);
        }
    }
    if (empty($cache_manager[$group])) {
        // ако групата е празна, да се изтрие
        unset($cache_manager[$group]);
    }

    // обновява се информацията за кеш менижъра
    Cache::forever('_cache_manager', $cache_manager);
    return $cache_manager ?? [];

}
