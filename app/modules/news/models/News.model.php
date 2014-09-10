<?php

class News extends BaseModel {

	protected $guarded = array();

	protected $table = 'news';

    ## http://laravel.ru/articles/odd_bod/your-first-model
    protected $fillable = array(
        'slug',
        'template',
        'type_id',
        'publication',
        'published_at'
    );

    public static $order_by = "news.published_at DESC,news.id DESC";

	public static $rules = array(
		#'news_ver_id' => 'required',
		#'seo_url' => 'alpha_dash',
	);

    public function metas() {
        return $this->hasMany('NewsMeta', 'news_id', 'id');
    }

    public function meta() {
        return $this->hasOne('NewsMeta', 'news_id', 'id')->where('language', Config::get('app.locale', 'ru'));
    }

    public function type() {
        return $this->hasOne('DicVal', 'id', 'type_id');
    }

    ##
    ## NEED TO TEST!
    ##
    public function seo() {
        /*
        ##
        ## Call to undefined method Illuminate\Database\Query\Builder::addEagerConstraints()
        ##
        #Helper::tad($this->meta->seo);
        $temp = $this->meta()->with('seo')->first();
        #Helper::tad($temp->seo); 
        return $temp->seo;
        */

        #return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'news_meta');

        #return $this->hasOne('NewsMeta', 'news_id', 'id')->where('language', Config::get('app.locale', 'ru'))->with('seo')->seo;
    }
}