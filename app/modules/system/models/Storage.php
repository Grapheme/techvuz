<?php

class Storage extends BaseModel {
	
	protected $guarded = array();
    public $table = 'storages';

    protected $fillable = array(
        'module',
        'name',
        'value',
    );

    public static $rules = array(
		'name' => 'required',
	);

    public function extract($unset = true) {
        $properties = json_decode($this->value);
        if (count($properties))
            foreach ($properties as $key => $value)
                $this->$key = $value;
        if ($unset)
            unset($this->value);
    }
}