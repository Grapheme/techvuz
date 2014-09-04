<?php

/**
 * PageMeta
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $language
 * @property string $template
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Seo $seo
 * @property-read \Page $page
 * @method static \Illuminate\Database\Query\Builder|\PageMeta whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageMeta wherePageId($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageMeta whereLanguage($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageMeta whereTemplate($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageMeta whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageMeta whereUpdatedAt($value) 
 */
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