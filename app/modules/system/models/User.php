<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * User
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property integer $active
 * @property string $password
 * @property string $photo
 * @property string $thumbnail
 * @property string $temporary_code
 * @property integer $code_life
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\Group[] $groups
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereSurname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePhoto($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereThumbnail($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereTemporaryCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCodeLife($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 */
class User extends Eloquent implements UserInterface, RemindableInterface {

	protected $table = 'users';

	protected $guarded = array();

	protected $hidden = array('password');

	public static $rules = array(
		'group_id' => 'required|integer',
		'name' => 'required',
		'email' => 'required|email|unique:users',
		'password' => 'required|min:6'
	);

	public static $rules_update = array(
		'group_id' => 'required|integer',
		'name' => 'required',
		#'surname' => 'required',
		'email' => 'required|email|unique:users,email',
		#'password' => 'required|min:6'
	);

	public static $rules_changepass = array(
		'password' => 'required|min:6'
	);

	public function getAuthIdentifier(){
		return $this->getKey();
	}

	public function getAuthPassword(){
		return $this->password;
	}

	public function getReminderEmail(){
		return $this->email;
	}

	public function group(){
		return $this->belongsTo('Group');
	}

	public function groups(){
		return $this->belongsToMany('Group');
	}
	
	public function getRememberToken(){
		
		return $this->remember_token;
	}

	public function setRememberToken($value){
		
		$this->remember_token = $value;
	}

	public function getRememberTokenName(){
		
		return 'remember_token';
	}
	
}