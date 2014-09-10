<?php

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