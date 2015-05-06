<?php

class DicFieldVal extends BaseModel {

	protected $guarded = array();

	public $table = 'dictionary_fields_values';
    #public $timestamps = false;

	#public static $order_by = "name ASC";

    protected $fillable = array(
        'dicval_id',
        'language',
        'key',
        'value',
    );

	public static $rules = array(
        'dicval_id' => 'required',
        'key' => 'required',
	);

    /**
     * Связь возвращает родительскую запись DicVal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dicval() {
        return $this->belongsTo('DicVal', 'dicval_id');
    }

}