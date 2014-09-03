<?php

class NewsMeta extends BaseModel {

	protected $guarded = array();

	protected $table = 'news_meta';

    ## http://laravel.ru/articles/odd_bod/your-first-model
    protected $fillable = array(
        'news_id',
        'language',
        'title',
        'preview',
        'content',
        'photo_id',
        'gallery_id',
    );

    public static $order_by = 'created_at DESC,updated_at DESC';

	public static $rules = array(
		#'title' => 'required',
		#'seo_url' => 'alpha_dash',
	);

    public function news() {
        return $this->belongsTo('News', 'news_id', 'id');
    }

    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'news_meta');
    }

    public function photo() {
        return $this->hasOne('Photo', 'id', 'photo_id');
    }

    public function gallery() {
        return $this->hasOne('Gallery', 'id', 'gallery_id');
    }

}