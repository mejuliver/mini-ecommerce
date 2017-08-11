<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class items_payment_type extends Model
{
    
    protected $table = "items_payment_type";

    public function item(){
    	return $this->belongsTo('App\items','item_id','item_id');
    }
    public function payment_type(){
    	return $this->hasOne('App\payment_types','payment_id','payment_id');
    }

}
