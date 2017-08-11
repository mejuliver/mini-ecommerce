<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'settings_id';

    public function users_settings(){
    	return $this->belongsTo('App\users_settings','settings_id','settings_id');
    }

}
