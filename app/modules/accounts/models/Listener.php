<?php

/**
 * Listener
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $organization_id
 * @property string $fio
 * @property string $position
 * @property string $postaddress
 * @property string $phone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Listener whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener whereOrganizationId($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener whereFio($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener wherePostaddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Listener whereUpdatedAt($value)
 */
class Listener extends BaseModel {

    protected $table = 'listeners';

    protected $guarded = array();

    public static $rules = array(
        'group_id' => 'required|integer',
        'organization_id' => 'required|integer',
        'fio' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'position' => 'required',
        'postaddress' => 'required',
        'phone' => 'required',
        'education' => 'required',
        'place_work' => 'required',
        'year_study' => 'required|integer',
        'specialty' => 'required',
    );

    public static $update_rules = array(
        'fio' => 'required',
        'phone' => 'required',
        'position' => 'required',
        'postaddress' => 'required',
        'phone' => 'required',
        'education' => 'required',
        'place_work' => 'required',
        'year_study' => 'required|integer',
        'specialty' => 'required',
    );

    public static $moderator_rules = array(
        'email' => 'required|email',
        'active' => 'required',
        'fio' => 'required',
        'phone' => 'required',
        'position' => 'required',
        'postaddress' => 'required',
        'phone' => 'required',
        'education' => 'required',
        'place_work' => 'required',
        'year_study' => 'required|integer',
        'specialty' => 'required',
    );
}