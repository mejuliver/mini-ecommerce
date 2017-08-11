<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class users_settings extends Model{
   
    public function settings(){
    	return $this->hasOne('App\settings','settings_id','settings_id');
    }
    public function profile(){
    	return $this->belongsTo('App\profile','username','username');
    }
}
