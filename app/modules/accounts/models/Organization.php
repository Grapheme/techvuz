<?php

/**
 * Organization
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $fio_manager
 * @property string $manager
 * @property string $statutory
 * @property string $inn
 * @property string $kpp
 * @property string $postaddress
 * @property integer $account_type
 * @property string $account_number
 * @property string $bank
 * @property string $bik
 * @property string $name
 * @property string $phone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereFioManager($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereManager($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereStatutory($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereKpp($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization wherePostaddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereAccountType($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereAccountNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereBank($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereBik($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Organization whereUpdatedAt($value)
 */
class Organization extends BaseModel {

	protected $table = 'organizations';

	protected $guarded = array();

	public static $rules = array(
		'group_id' => 'required|integer',
		'name' => 'required',
		'email' => 'required|email',
		'phone' => 'required',
		'title' => 'required',
		'fio_manager' => 'required',
		'manager' => 'required',
		'statutory' => 'required',
		'inn' => 'required',
		'kpp' => 'required',
		'postaddress' => 'required',
		'account_type' => 'required',
		'account_number' => 'required',
		'bank' => 'required',
		'bik' => 'required',

		'consent' => 'required|integer',
	);
    public static $update_rules = array(
		'name' => 'required',
		'phone' => 'required',
		'title' => 'required',
		'fio_manager' => 'required',
		'manager' => 'required',
		'statutory' => 'required',
		'inn' => 'required',
		'kpp' => 'required',
		'postaddress' => 'required',
		'account_type' => 'required',
		'account_number' => 'required',
		'bank' => 'required',
		'bik' => 'required',
	);

}