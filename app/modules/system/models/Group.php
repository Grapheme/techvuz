<?php

/**
 * Group
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property string $dashboard
 * @property string $start_url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Group whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Group whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Group whereDesc($value) 
 * @method static \Illuminate\Database\Query\Builder|\Group whereDashboard($value) 
 * @method static \Illuminate\Database\Query\Builder|\Group whereStartUrl($value) 
 * @method static \Illuminate\Database\Query\Builder|\Group whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Group whereUpdatedAt($value) 
 */
class Group extends Eloquent {
	
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required|unique:groups',
		'desc' => 'required|unique:groups',
		'dashboard' => 'required'
	);

	public static $rules_update = array(
		'name' => 'required',
		'desc' => 'required',
		'dashboard' => 'required'
	);

    ## Количество юзеров в группе
	public function count_users() {
		return User::where('group_id', $this->id)->count();
	}
	
}