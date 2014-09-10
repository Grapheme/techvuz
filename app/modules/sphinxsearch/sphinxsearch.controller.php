<?php

class SphinxsearchController extends \BaseController {

    public static $name = 'sphinxsearch';
    public static $group = 'production';

    public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group . "/" . self::$name,
            'tpl'  => static::returnTpl(),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
    }

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        Route::post('search/request',$class.'@headerSearch');
        Route::post('search/request/{text}',$class.'@headerSearch');
    }

    public static function returnExtFormElements() {

        return null;
    }

    public static function returnActions() {

        return null;
    }

    public static function returnInfo() {

        return null;
    }

    public function headerSearch($text = null){

        if(is_null($text)):
            $text = Input::get('search_request');
        endif;

        if(!empty($text)):
            return Redirect::to('/search?query='.$text);
        else:
            return Redirect::back();
        endif;
    }

    public static function search($searchText){

        $indexes = self::readIndexes($searchText);
        $result['channels'] = self::getChannelsModels($indexes['channels']);
        $result['products'] = self::getProductsModels($indexes['products']);
        $result['reviews'] = self::getReviewsModels($indexes['reviews']);
        $result['pages'] = self::getPagesModels($indexes['pages']);
        return $result;
    }

    private static function readIndexes($searchText){

        $channels = SphinxSearch::search($searchText, 'channelsIndex')->setFieldWeights(array('title' => 10, 'short' => 8, 'desc' => 6, 'category_title' => 1))
            ->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED)
            ->SetSortMode(\Sphinx\SphinxClient::SPH_SORT_RELEVANCE, "@weight DESC")
            ->limit(6)->get();

        $products = SphinxSearch::search($searchText, 'productsIndex')->setFieldWeights(array('title' => 10, 'short' => 8, 'desc' => 6, 'category_title' => 1))
            ->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED)
            ->SetSortMode(\Sphinx\SphinxClient::SPH_SORT_RELEVANCE, "@weight DESC")
            ->limit(6)->get();

        $reviews = SphinxSearch::search($searchText, 'reviewsIndex')->setFieldWeights(array('name' => 10, 'name' => 8, 'details' => 1))
            ->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED)
            ->SetSortMode(\Sphinx\SphinxClient::SPH_SORT_RELEVANCE, "@weight DESC")
            ->limit(6)->get();

        $pages = SphinxSearch::search($searchText, 'pagesIndex')->setFieldWeights(array('seo_title' => 10, 'seo_description' => 10, 'seo_h1' => 10, 'content' => 8))
            ->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED)
            ->SetSortMode(\Sphinx\SphinxClient::SPH_SORT_RELEVANCE, "@weight DESC")
            ->limit(6)->get();

        return compact('channels','products','reviews','pages');
    }

    private static function getChannelsModels($foundRecords){

        if($recordIDs = self::getValueInObject($foundRecords)):
            return Channel::whereIn('id',$recordIDs)->get();
        endif;
        return null;
    }

    private static function getProductsModels($foundRecords){

        if($recordIDs = self::getValueInObject($foundRecords)):
            if($products = Product::whereIn('id',$recordIDs)->with('images')->get()):
                return $products->toArray();
            endif;
        endif;
        return null;
    }

    private static function getReviewsModels($foundRecords){

        if($recordIDs = self::getValueInObject($foundRecords)):
            return Reviews::whereIn('id',$recordIDs)->with('meta')->with('photo')->get();
        endif;
        return null;
    }

    private static function getPagesModels($foundRecords){

        if($recordIDs = self::getValueInObject($foundRecords)):
            return I18nPage::whereIn('id',$recordIDs)->with('metas')->get();
        endif;
        return null;

    }
}