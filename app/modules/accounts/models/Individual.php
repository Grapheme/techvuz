<?php

/**
 * Individual
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $fio
 * @property string $position
 * @property string $inn
 * @property string $postaddress
 * @property string $phone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Individual whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual whereFio($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual wherePostaddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Individual whereUpdatedAt($value)
 */
class Individual extends BaseModel {

    protected $table = 'individuals';

    protected $guarded = array();

    public static $rules = array(
        'group_id' => 'required|integer',
        'fio' => 'required',
        'fio_rod' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'passport_seria' => 'required',
        'passport_number' => 'required',
        'passport_data' => 'required',
        'passport_date' => 'required',
        'code' => 'required',
        'postaddress' => 'required',

        'consent' => 'required|integer',
    );

}