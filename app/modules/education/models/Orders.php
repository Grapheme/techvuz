<?php

/**
 * Orders
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $number
 * @property boolean $completed
 * @property \PaymentStatus $payment_status
 * @property string $payment_date
 * @property integer $payment_discount
 * @property boolean $close_status
 * @property string $close_date
 * @property boolean $archived
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\OrderListeners[] $listeners
 * @method static \Illuminate\Database\Query\Builder|\Orders whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCompleted($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentDateReal($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCloseStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCloseDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereArchived($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereUpdatedAt($value)
 */
class Orders extends BaseModel {

    protected $guarded = array();

    protected $table = 'orders';

    protected $fillable = array('user_id','number','completed','discount');

    public static $order_by = "number";

    public static $rules = array();

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function listeners() {
        return $this->hasMany('OrderListeners', 'order_id');
    }

    public function payment(){

        return $this->hasOne('PaymentStatus','id','payment_status');
    }

    public function organization() {
        return $this->belongsTo('User_organization', 'user_id');
    }

    public function individual() {
        return $this->belongsTo('User_individual', 'user_id');
    }

    public function payment_numbers(){

        return $this->hasMany('OrderPayments','order_id');
    }

    public function getLastOrderNumber($next = FALSE){

        #->where(DB::raw('YEAR(created_at)'),'=',date('Y'))
        $lastNumber = (int) $this->where('completed',1)
            ->orderBy('number','DESC')
            ->where('archived',0)
            ->pluck('number');
        if ($next):
            return $lastNumber+1;
        else:
            return $lastNumber;
        endif;
    }

    public function getLastOrderCompletionNumber($next = FALSE){

        $lastNumber = (int) $this->where('completed',1)
            ->orderBy('number_completion','DESC')
            ->where('archived',0)
            ->pluck('number_completion');
        if ($next):
            return $lastNumber+1;
        else:
            return $lastNumber;
        endif;
    }

    public function getLastOrderEnrollmentNumber($next = FALSE){

        $lastNumber = (int) $this->where('completed',1)
            ->orderBy('number_enrollment','DESC')
            ->where('archived',0)
            ->pluck('number_enrollment');
        if ($next):
            return $lastNumber+1;
        else:
            return $lastNumber;
        endif;
    }

    public function getLastFreeOrderNumber(){

        $freeNumber = 0;
        $allNumbers = $this->where('completed',1)
            ->where(DB::raw('YEAR(created_at)'),'=',date('Y'))
            ->orderBy('number','ASC')
            ->where('archived',0)
            ->lists('number');
        foreach($allNumbers as $index => $number):
            if (isset($allNumbers[$index+1]) && $allNumbers[$index+1] != $number+1):
                $freeNumber = $number+1;
                break;
            endif;
        endforeach;
        return $freeNumber != 0 ? $freeNumber : $this->getLastOrderNumber(TRUE);
    }

    public function contract(){
        return $this->hasOne('Upload','id','contract_id');
    }

    public function invoice(){
        return $this->hasOne('Upload','id','invoice_id');
    }

    public function act(){
        return $this->hasOne('Upload','id','act_id');
    }
}