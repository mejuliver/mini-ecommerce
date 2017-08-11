<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class items extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';

    public function item_images(){
        return $this->hasMany('App\item_images','item_id','item_id');
    }
    public function item_review(){
        return $this->hasMany('App\items_review','item_id','item_id');
    }
    public function profile(){
    	return $this->belongsTo('App\profile','username','username');
    }

    public function item_tags(){
        return $this->hasMany('App\item_tags','item_id','item_id');
    }
    public function item_categories(){
        return $this->hasMany('App\item_categories','item_id','item_id');
    }

    public function cart(){
        return $this->hasMany('App\cart','item_id','item_id');
    }

    public function wishlists(){
        return $this->hasMany('App\wishlists','item_id','item_id');
    }
    public function items_payment_type(){
        return $this->hasOne('App\items_payment_type','item_id','item_id');
    }

}
