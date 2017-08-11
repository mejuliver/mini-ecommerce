<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment_types extends Model
{

    protected $table = "payment_types";
    protected $primaryKey = "payment_id";

    public function order(){
    	return $this->belongsTo('App\orders','payment_id');
    }
    public function items_payment_type(){
    	return $this->belongsTo('App\items_payment_type','payment_id');
    }
}
