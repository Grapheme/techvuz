<?php

/**
 * Page
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $template
 * @property integer $type_id
 * @property boolean $publication
 * @property boolean $start_page
 * @property boolean $in_menu
 * @property integer $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\PageBlock[] $blocks
 * @property-read \Illuminate\Database\Eloquent\Collection|\PageMeta[] $metas
 * @property-read \PageMeta $meta
 * @method static \Illuminate\Database\Query\Builder|\Page whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereSlug($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereTemplate($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereTypeId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page wherePublication($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereStartPage($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereInMenu($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereOrder($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereUpdatedAt($value) 
 */
class Page extends BaseModel {

	protected $guarded = array();

    protected $table = 'pages';

    protected $orderBy = 'order ASC, created_at DESC';

    public static $rules = array(
	    'name' => 'required',
		#'seo_url' => 'alpha_dash'
	);

    protected $fillable = array(
        'name',
        'slug',
        'template',
        'type_id',
        'publication',
        'start_page',
        'in_menu',
        'order',
    );


    public function blocks() {
        return $this->hasMany('PageBlock', 'page_id', 'id')->orderBy('order');
    }

    public function metas() {
        return $this->hasMany('PageMeta', 'page_id', 'id');
    }

    public function meta() {
        return $this->hasOne('PageMeta', 'page_id', 'id')->where('language', Config::get('app.locale', 'ru'));
    }

    public function blocksBySlug() {
        #$return = $this;
        if (@count($this->blocks)) {
            $temp = array();
            foreach ($this->blocks as $b => $block) {
                $this->blocks[$block->slug] = $block;
                unset($this->blocks[$b]);
            }
        }
        return $this;
    }

    public function block($slug = false) {

        if (!$slug || !@count($this->blocks) || !@is_object($this->blocks[$slug]) || !@is_object($this->blocks[$slug]->meta))
            return false;

        #return $this->blocks[$slug]->meta->content;

        return DbView::make($this->blocks[$slug]->meta)->field('content')->with(array())->render();
    }

}
