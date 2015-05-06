<?php

class DicValMeta extends BaseModel {

	protected $guarded = array();

	public $table = 'dictionary_values_meta';
    #public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'dicval_id',
        'language',
        'name',
    );

	public static $rules = array(
		'dicval_id' => 'required',
		'language' => 'required',
	);

    /**
     * Связь возвращает запись для текущей META
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dicval() {
        return $this->belongsTo('DicVal', 'dicval_id');
    }

}