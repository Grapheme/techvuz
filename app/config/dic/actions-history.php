<?php

return array(

    'fields' => function () {
        return array();
    },
    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        return $menus;
    },
    'group_actions' => array(
        'moderator' => function() {
            return array(
                'dicval_create' => 0,
                'dicval_edit' => 0,
                'dicval_delete' => 0,
            );
        },
    ),
    'first_line_modifier' => function($line, $dic, $dicval) {
        $actions_types =  Config::get('temp.actions_types');
        return @$actions_types[$dicval->action_id].'. '.$dicval->title;
    },
    'second_line_modifier' => function($line, $dic, $dicval) {
        return $dicval->created_at->format("d.m.Y H:i");
    },
    'actions' => function($dic, $dicval) { },
    'hooks' => array(

        'before_index_view' => function ($dic, $dicvals) {
            $actions = array();
            foreach(Dictionary::where('slug', 'actions-types')->first()->values()->get() as $action):
                $actions[$action->id] = $action->name;
            endforeach;
            Config::set('temp.actions_types', $actions);
        },
    ),
    'seo' => false,
);
