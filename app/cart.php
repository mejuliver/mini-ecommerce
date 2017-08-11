<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    protected $table = "cart";

    public function order(){
    	return $this->belongsTo('App\orders','order_id','order_id');
    }

    public function item(){
    	return $this->belongsTo('App\items','item_id','item_id');
    }
}
