<?php

class Photo extends Eloquent {
	protected $guarded = array();

	protected $table = 'photos';

	public static $order_by = 'photos.id DESC';

	public function thumb() {
		#return link::to(Config::get('app-default.galleries_thumb_dir')) . "/" . $this->name;
		return URL::to(Config::get('app-default.galleries_thumb_public_dir') . "/" . $this->name);
	}

	public function full() {
		return $this->path();
	}

	public function path() {
		#return link::to(Config::get('app-default.galleries_photo_dir')) . "/" . $this->name;
		return URL::to(Config::get('app-default.galleries_photo_public_dir') . "/" . $this->name);
	}

    public function fullpath() {
        #return link::to(Config::get('app-default.galleries_photo_dir')) . "/" . $this->name;
        return str_replace('//', '/', public_path(Config::get('app-default.galleries_photo_public_dir') . "/" . $this->name));
    }

}