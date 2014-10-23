<?php

class Photo extends Eloquent {
	protected $guarded = array();

	protected $table = 'photos';

	public static $order_by = 'photos.id DESC';

	public function thumb() {
		return URL::to(Config::get('site.galleries_thumb_public_dir') . "/" . $this->name);
	}

	public function full() {
		return $this->path();
	}

	public function path() {
		return URL::to(Config::get('site.galleries_photo_public_dir') . "/" . $this->name);
	}
}