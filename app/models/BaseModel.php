<?php

class BaseModel extends Eloquent {
	
	public static $errors = array();

    public function rules() {
        #Helper::dd(static::$rules);
        return static::$rules;
    }

    public function sort() {
        Helper::dd(static::$orderBy);
        $return = $this;
        //if ()
        return static::$rules;
    }

	public static function validate($data, $rules = NULL, $messages = array()){
		
		if(is_null($rules)):
			$rules = static::$rules;
		endif;
		$validation = Validator::make($data, $rules, $messages);
		if($validation->fails()):
			static::$errors = $validation->messages()->all();
			return FALSE;
		endif;
		return TRUE;
	}

    public static function whereSlug($slug) {
        return self::firstOrNew(array('slug' => $slug));
    }

    public static function whereId($id) {
        return self::firstOrNew(array('id' => $id));
    }

    /*
    public function lists2($value, $key = false) {
        Helper::dd($this);
    }
    */

}