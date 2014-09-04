<?php

/**
 * PageBlock
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $name
 * @property string $slug
 * @property string $desc
 * @property string $template
 * @property integer $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\PageBlockMeta[] $metas
 * @property-read \PageBlockMeta $meta
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock wherePageId($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereSlug($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereDesc($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereTemplate($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereOrder($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\PageBlock whereUpdatedAt($value) 
 */
class PageBlock extends BaseModel {

	protected $guarded = array();

	protected $table = 'pages_blocks';

	public static $rules = array(
		#'title' => 'required',
		#'seo_url' => 'alpha_dash',
	);


    public function metas() {
        return $this->hasMany('PageBlockMeta', 'block_id', 'id');
    }

    public function meta() {
        return $this->hasOne('PageBlockMeta', 'block_id', 'id')->where('language', Config::get('app.locale', 'ru'));
    }

    public function metasByLang() {
        $return = $this;
        if (@count($this->metas)) {
            $temp = array();
            foreach ($this->metas as $m => $meta) {
                #$temp[$meta->language] = $meta;
                $this->metas[$meta->language] = $meta;
                unset($this->metas[$m]);
            }
            #$this->metas = $temp;
        }
        #$return->name = "!!!";
        return $return;
    }

}