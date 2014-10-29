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
        'version_of',
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

    public function seos() {
        return $this->hasMany('Seo', 'unit_id', 'id')->where('module', 'Page');
    }

    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'Page')->where('language', Config::get('app.locale', 'ru'));
    }

    public function versions() {
        return $this->hasMany('Page', 'version_of', 'id')->orderBy('updated_at', 'DESC');
    }

    public function original_version() {
        return $this->hasOne('Page', 'id', 'version_of');
    }

    /**
     * Depricated - use $page->extract(true);
     *
     * @return $this
     */
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

        if (
            !$slug || !@count($this->blocks) || !@is_object($this->blocks[$slug])
            #|| (!@isset($this->blocks[$slug]->content) && !@is_object($this->blocks[$slug]->meta))
        )
            return false;

        #Helper::tad($this);

        $content_container = false;
        if (isset($this->blocks[$slug]->content))
            $content_container = $this->blocks[$slug];
        elseif (isset($this->blocks[$slug]->meta) && !is_null($this->blocks[$slug]->meta))
            $content_container = $this->blocks[$slug]->meta;

        if (!$content_container)
            return '';

        #Helper::dd($this->blocks[$slug]->meta->content);
        ## Without blade syntax compile
        #return $this->blocks[$slug]->meta->content;

        ## Force template compile
        if ($force_compile)
            $content_container->updated_at = date('Y-m-d H:i:s');

        ## Without updated_at - COMPILE ONLY ONCE!
        #unset($this->blocks[$slug]->meta->updated_at);

        ## Return compiled field of the model
        return DbView::make($content_container)->field('content')->with($variables)->render();
    }

    public function extract($unset = false) {

        #Helper::ta($this);

        ## Extract SEO
        if (isset($this->seos)) {
            #Helper::tad($this->seos);
            if (count($this->seos) == 1 && count(Config::get('app.locales')) == 1) {
                $app_locales = Config::get('app.locales');
                foreach ($app_locales as $locale_sign => $locale_name)
                    break;
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$locale_sign] = $seo;
                    break;
                }
                unset($this->seos[0]);
                #Helper::tad($this->seos);
            } else {
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$seo->language] = $seo;
                    #Helper::d($s . " != " . $seo->language);
                    if ($s != $seo->language || $s === 0)
                        unset($this->seos[$s]);
                }
            }
        }

        ## Extract metas
        if (isset($this->metas)) {
            foreach ($this->metas as $m => $meta) {
                $this->metas[$meta->language] = $meta;
                if ($m != $meta->language || $m === 0)
                    unset($this->metas[$m]);
            }
        }

        ## Extract meta
        if (isset($this->meta)) {
            if ($this->meta->template)
                $this->template = $this->meta->template;
            $this->language = $this->meta->language;
            if ($unset)
                unset($this->meta);
        }

        ## Extract blocks
        if (isset($this->blocks)) {
            foreach ($this->blocks as $b => $block) {
                if (isset($block->meta) && 1) {
                    if ($block->meta->name)
                        $block->name = $block->meta->name;
                    if ($block->meta->template)
                        $block->template = $block->meta->template;
                    $block->content = $block->meta->content;
                    if ($unset)
                        unset($block->meta);
                }
                $this->blocks[$block->slug] = $block;
                if ($b != $block->slug || $b === 0)
                    unset($this->blocks[$b]);
            }
        }

        #Helper::ta($this);

        return $this;
    }

}
