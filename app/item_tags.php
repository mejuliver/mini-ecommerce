<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_tags extends Model
{
    
    protected $table = "item_tags";

    public function item(){
    	return $this->belongsTo('App\items','item_id','item_id');
    }
    public function tags(){
    	return $this->hasOne('App\tags','tag_id','tag_id');
    }
}
