<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    protected $table = "orders";

    public function payment_type(){
    	return $this->hasOne('App\payment_types','payment_id','payment_id');
    }
    public function profile(){
    	return $this->hasOne('App\profile','username','username');
    }
    public function cart(){
        return $this->hasMany('App\cart','order_id','order_id');
    }
}
