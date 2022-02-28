<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public static function find($id)
    {
        return Setting::where('id', $id)->first();
    }
    
    public static function findByKey($key)
    {
        return Setting::where('key', $key)->firstOrFail();
    }

    public static function all()
    {
        return Setting::orderBy('key', 'asc')->get();
    }

    public static function paginate()
    {
        return Setting::orderBy('key', 'asc')->paginate();
    }

    public static function create(array $input)
    {
        return Setting::create($input);
    }

    public static function update($setting, array $input)
    {
        $setting->update($input);
        return $setting;
    }
    
    public static function updateOrCreate($key, $value = null)
    {
        return Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function delete($setting)
    {
        $setting->delete();
    }
}
