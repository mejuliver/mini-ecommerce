<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_categories extends Model{
    
    protected $table = "item_categories";

    public function item(){
    	return $this->belongsTo('App\items','item_id','item_id');
    }
    public function category(){
    	return $this->hasOne('App\categories','cat_id','cat_id');
    }
}
