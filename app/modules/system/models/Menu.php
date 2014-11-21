<?php

class Menu extends Storage {
	
	protected $guarded = array();
    public $table = 'storages';

    protected $fillable = array(
        'module',
        'name',
        'value',
    );

    public static $rules = array(
		'name' => 'required',
	);

    public function extract($unset = true) {
        $properties = json_decode($this->value);
        if (count($properties))
            foreach ($properties as $key => $value)
                $this->$key = $value;
        if ($unset)
            unset($this->value);
    }

    public static function draw($slug, $options = false) {

        $menu = Storage::where('module', 'menu')->where('name', $slug)->first();
        $value = json_decode($menu->value, 1);
        Helper::dd($value);
        #$menu = self::get_menu_level($value->items, $options);
    }

    public static function show($menu_slug) {
        return (new MenuConstructor($menu_slug))->draw();
    }

    public static function placement($placement_slug) {

        $menu_placement_value = Config::get('temp.menu_placement_value');
        #var_dump($menu_placement);

        if (is_null($menu_placement_value)) {
            $menu_placement = Storage::firstOrNew(array('module' => 'menu_placement', 'name' => 'menu_placement'));
            $menu_placement_value = is_object($menu_placement) ? (array)json_decode($menu_placement->value, 1) : array();
            Config::set('temp.menu_placement_value', $menu_placement_value);
        }

        $menu_slug = @$menu_placement_value[$placement_slug] ?: false;

        return $menu_slug ? self::show($menu_slug) : false;
    }

    /*
    public static function get_menu_level($level, $options = false) {
        if (!is_array($level) || !count($level))
            return false;
        $return = array('<ul>');
        foreach ($level as $item) {

            $return[] = '<li><a href="">' . $item . '</a></li>';
        }
        $return[] = '</ul>';
    }
    */

}