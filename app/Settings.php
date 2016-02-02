<?php

namespace App;

class Settings
{
    protected static $cache = [];
    
    protected static function writeCache($key, $value) {
        self::$cache[$key] = $value;
    }
    
    protected static function readCache($key) {
        if(array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }
        
        return null;
    }
    
    public static function get($key, $default = null) {
        $cached = self::readCache($key);
        if($cached !== null) {
            return $cached;
        }
        
        $setting = \DB::table('settings')->where('key', $key);
        if(!$setting->count()) {
            return $default;
        }
        
        return $setting->value('value');
    }
    
    public static function set($key, $value) {
        $setting = \DB::table('settings')->where('key', $key);
        
        if(!$setting->count()) {
            \DB::table('settings')->insert([
                'key' => $key, 
                'value' => $value,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        } else {
            $setting->where('key', $key)->update([
                'value' => $value,
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
        
        self::writeCache($key, $value);
    }
}
