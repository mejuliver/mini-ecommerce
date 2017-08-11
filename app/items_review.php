<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class items_review extends Model
{

	protected $table = 'items_review';

    public function item(){
    	return $this->belongsTo('App\items','item_id','item_id');
    }

    public function profile(){
    	return $this->belongsTo('App\profile','username','username');
    }

}
