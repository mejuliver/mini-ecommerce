<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roles_id extends Model
{
    protected $fillable = [
        'role_id', 'username',
    ];

    protected $table = "roles_id";

    public function role(){
    	return $this->hasOne('App\role','role_id','role_id');
    }
    public function profile(){
    	return $this->belongsTo('App\profile','username','username');
    }
}
