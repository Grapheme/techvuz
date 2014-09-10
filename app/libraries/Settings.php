<?php

/**
 * Class for work with table "settings"
 *
 */
class Settings {

	public static function get($name = false, $default = false) {
		
        if (!$name)
            return false;

        $set = Setting::where('name', $name)->first();
        return is_object($set) ? $set->value : $default;
	}


	public static function set($name = false, $value = false) {
		
        if (!$name)
            return false;

        $set = Setting::where('name', $name)->first();
        if (is_object($set)) {
            $set->value = $value;
            $set->save();
            $set->touch();
        } else {
            Setting::create(
                array(
                    'name'  => $name,
                    'value' => $value
                )
            );
        }

        return true;
	}


	public static function increase($name = false) {
		
        if (!$name)
            return false;

        $set = Setting::where('name', $name)->first();
        if (is_object($set)) {
            $set->value = $set->value+1;
            $set->save();
            $set->touch();
        } else {
            Setting::create(
                array(
                    'name'  => $name,
                    'value' => 1
                )
            );
        }

        return true;
	}


	public static function decrease($name = false) {
		
        if (!$name)
            return false;

        $set = Setting::where('name', $name)->first();
        if (is_object($set)) {
            $set->value = $set->value > 0 ? $set->value-1 : 0;
            $set->save();
            $set->touch();
        } else {
            Setting::create(
                array(
                    'name'  => $name,
                    'value' => 0
                )
            );
        }

        return true;
	}


	public static function getint($name = false, $default = false) {
		
        return (int)self::get($name, $default);
	}

}