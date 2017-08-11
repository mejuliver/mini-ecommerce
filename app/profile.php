<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class profile extends Model
{
    protected $fillable = [
        'username', 'first_name', 'middle_name', 'last_name', 'img', 'age', 'address', 'email'
    ];

    protected $table = "profiles";
    protected $primaryKey = "username";
    public $incrementing = false;
    //has one account the user's model
    public function user(){
    	return $this->belongsTo('App\User','username','username');
    }
    //has many role unto the users roles model
    public function roles_id(){
    	return $this->hasMany('App\roles_id','username','username');
    }
    //has many permission on the users permissions model
    public function permissions_id(){
        return $this->hasMany('App\permissions_id','username','username');
    }
    //has many on the users settings model
    public function users_settings(){
        return $this->hasMany('App\users_settings','username','username');
    }
    //has many on the messages model
    public function messages(){
        return $this->hasMany('App\messages','username');
    }
    //has many on notification model
    public function notifications(){
        return $this->hasMany('App\notifications','username');
    }
    //has many on the items model
    public function item(){
        return $this->hasMany('App\items','username','username');
    }
    //has many tags on tags model
    public function tags(){
        return $this->hasMany('App\tags','username','username');
    }
    //has many on item images model
    public function item_images(){
        return $this->hasMany('App\item_images','username','username');
    }
    //has many on the wishlists model
    public function wishlists(){
        return $this->hasMany('App\wishlists','username','username');
    }
    //has many on the item review model
    public function item_review(){
        return $this->hasMany('App\item_review','username','username');
    }

}
