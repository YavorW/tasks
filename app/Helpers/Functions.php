<?php

use App\Models\Log;
use Illuminate\Support\Facades\Http;

define('paginate', 9); // по колко на брой елемента да има при странициране


function convertUrlsToLinks($input) {
    $pattern = '@(http(s)?://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
    return $output = preg_replace($pattern, '<a href="http$2://$3" target="_blank" referrerpolicy="no-referrer">$0</a>', $input);
 }

/**
 * Логване на активност
 * @param $model_type
 * @param $method
 * @param null $model_id
 * @param null $user_id
 * @param null $message
 * @return bool|mixed|null
 */
function activity_log($model_type, $method, $model_id = null, $user_id = null, $message = null)
{
    try {
        $log = new Log();
        $log->model_type = $model_type;
        $log->method = $method;
        $log->model_id = $model_id;
        $log->user_id = $user_id;
        $log->message = $message;
        $log->save();
        return $log->id;
    } catch (\Exception $e) {
        return false;
    }
    return null;
}

/**
 * Заменя id-то на масива от вид на selector[index] => selector-index
 *
 * @param string $id
 * @return string
 */
function changeId($id)
{
    $id = str_replace(']', '', $id);
    $id = str_replace('[', '-', $id);
    return $id;
}

/**
 * Променя параметрите на текущият път
 * използва се в компонента x-table
 */
function changeRouteParams(array $params = []): string
{
    /** @var array сегашните query параметри*/
    $crp = request()->query();
    $crp = array_merge($crp, $params);
    return route(request()->route()->getName(), $crp);
}

/**
 * преработване на данни с псевдонимизация
 * @param string $string
 * @param bool $back при true върни оригинала
 */
function pseudonymization(?string $string, bool $back = false): string
{
    $pseudonymization = ['0' => 'F', '1' => 'o', '2' => '1', '3' => 'N', '4' => '3', '5' => 'K', '6' => 'l', '7' => 'p', '8' => 't', '9' => 'q', 'a' => 'v', 'b' => 'h', 'c' => 'n', 'd' => 'k', 'e' => 'm', 'f' => 'c', 'g' => 'G', 'h' => 'r', 'i' => 'B', 'j' => 's', 'k' => 'a', 'l' => 'A', 'm' => 'Q', 'n' => 'M', 'o' => 'd', 'p' => '7', 'q' => 'P', 'r' => 'H', 's' => 'D', 't' => '4', 'u' => 'V', 'v' => 'S', 'w' => '6', 'x' => '9', 'y' => 'j', 'z' => 'R', 'A' => 'z', 'B' => 'T', 'C' => 'Z', 'D' => '0', 'E' => 'e', 'F' => 'g', 'G' => 'X', 'H' => 'x', 'I' => 'Y', 'J' => 'f', 'K' => 'b', 'L' => 'y', 'M' => 'U', 'N' => 'E', 'O' => '2', 'P' => 'u', 'Q' => 'L', 'R' => 'C', 'S' => 'W', 'T' => '8', 'U' => 'J', 'V' => '5', 'W' => 'O', 'X' => 'i', 'Y' => 'I', 'Z' => 'w'];
    $original = ['F' => '0', 'o' => '1', '1' => '2', 'N' => '3', '3' => '4', 'K' => '5', 'l' => '6', 'p' => '7', 't' => '8', 'q' => '9', 'v' => 'a', 'h' => 'b', 'n' => 'c', 'k' => 'd', 'm' => 'e', 'c' => 'f', 'G' => 'g', 'r' => 'h', 'B' => 'i', 's' => 'j', 'a' => 'k', 'A' => 'l', 'Q' => 'm', 'M' => 'n', 'd' => 'o', '7' => 'p', 'P' => 'q', 'H' => 'r', 'D' => 's', '4' => 't', 'V' => 'u', 'S' => 'v', '6' => 'w', '9' => 'x', 'j' => 'y', 'R' => 'z', 'z' => 'A', 'T' => 'B', 'Z' => 'C', '0' => 'D', 'e' => 'E', 'g' => 'F', 'X' => 'G', 'x' => 'H', 'Y' => 'I', 'f' => 'J', 'b' => 'K', 'y' => 'L', 'U' => 'M', 'E' => 'N', '2' => 'O', 'u' => 'P', 'L' => 'Q', 'C' => 'R', 'W' => 'S', '8' => 'T', 'J' => 'U', '5' => 'V', 'O' => 'W', 'i' => 'X', 'I' => 'Y', 'w' => 'Z'];
    $new_string = '';
    $lenght = mb_strlen($string);
    if ($back) {
        // преработено => оригинал
        for ($i = 0; $i < $lenght; ++$i) {
            $new_string .= in_array($string[$i], $original) ? $original[$string[$i]] : $string[$i];
        }
    } else {
        for ($i = 0; $i < $lenght; ++$i) {
            // оригинал -> преработено
            $new_string .= in_array($string[$i], $pseudonymization) ? $pseudonymization[$string[$i]] : $string[$i];
        }
    }

    return $new_string;
}

/**
 * 1 = 1st, 2 = 2nd, 3= 3rd, 4 = 4th ...
 *
 * @param integer $number
 * @return string
 */
function ordinal(int $number)
{
    $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function byteConvert($bytes)
{
    if ($bytes == 0)
        return "0.00 B";

    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $e = floor(log($bytes, 1024));

    return round($bytes/pow(1024, $e), 2).$s[$e];
}