<?php

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

    public function block($slug = false, $variables = array(), $force_compile = true) {

        if (!$slug || !@count($this->blocks) || !@is_object($this->blocks[$slug]) || !@is_object($this->blocks[$slug]->meta))
            return false;

        #Helper::dd($this->blocks[$slug]->meta->content);
        ## Without blade syntax compile
        #return $this->blocks[$slug]->meta->content;

        ## Force template compile
        if ($force_compile)
            $this->blocks[$slug]->meta->updated_at = date('Y-m-d H:i:s');

        ## Without updated_at - COMPILE ONLY ONCE!
        #unset($this->blocks[$slug]->meta->updated_at);

        ## Return compiled field of the model
        return DbView::make($this->blocks[$slug]->meta)->field('content')->with($variables)->render();
    }

}
