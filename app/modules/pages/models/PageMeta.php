<?php

class PageMeta extends BaseModel {

	protected $guarded = array();

	protected $table = 'pages_meta';

	public static $rules = array(
		#'title' => 'required',
		#'seo_url' => 'alpha_dash',
	);

    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'page_meta');
    }

    public function page() {
        return $this->belongsTo('Page', 'page_id', 'id');
    }

}