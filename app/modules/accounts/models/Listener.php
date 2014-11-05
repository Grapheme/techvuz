<?php

class Listener extends BaseModel {

    protected $table = 'listeners';

    protected $guarded = array();

    public static $rules = array(
        'group_id' => 'required|integer',
        'organization_id' => 'required|integer',
        'fio' => 'required',
        'fio_dat' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'position' => 'required',
        'postaddress' => 'required',
        'phone' => 'required',
        'education' => 'required',
        'education_document_data' => 'required',
        'educational_institution' => 'required',
        'specialty' => 'required',
    );

    public static $update_rules = array(
        'fio' => 'required',
        'fio_dat' => 'required',
        'phone' => 'required',
        'position' => 'required',
        'postaddress' => 'required',
        'phone' => 'required',
        'education' => 'required',
        'education_document_data' => 'required',
        'educational_institution' => 'required',
        'specialty' => 'required',
    );

    public static $moderator_rules = array(
        'email' => 'required|email',
        'active' => 'required',
        'fio' => 'required',
        'fio_dat' => 'required',
        'phone' => 'required',
        'position' => 'required',
        'postaddress' => 'required',
        'phone' => 'required',
        'education' => 'required',
        'education_document_data' => 'required',
        'educational_institution' => 'required',
        'specialty' => 'required',
    );

}