<?php

class Seo extends BaseModel {

	protected $guarded = array();

	protected $table = 'seo';

    ## http://laravel.ru/articles/odd_bod/your-first-model
    protected $fillable = array(
        'module',
        'unit_id',
        'language',
        'title',
        'description',
        'keywords',
        'url',
        'h1'
    );

	public static $rules = array(
		#'news_ver_id' => 'required',
		#'seo_url' => 'alpha_dash',
	);

}