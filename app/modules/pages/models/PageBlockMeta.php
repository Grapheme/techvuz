<?php

/**
 * PageBlockMeta
 *
 * @property integer $id
 * @property integer $block_id
 * @property string $name
 * @property string $content
 * @property string $language
 * @property string $template
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereBlockId($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereContent($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereLanguage($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereTemplate($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlockMeta whereUpdatedAt($value) 
 */
class PageBlockMeta extends BaseModel {

	protected $guarded = array();

	protected $table = 'pages_blocks_meta';

	public static $rules = array(
		#'title' => 'required',
		#'seo_url' => 'alpha_dash',
	);

}