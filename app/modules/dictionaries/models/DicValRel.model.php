<?php

class DicValRel extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary_values_rel';
    #public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'dicval_parent_id',
        'dicval_child_id',
        'dicval_child_dic',
    );

}