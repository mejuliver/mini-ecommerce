<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wishlists extends Model
{
    protected $table = 'wishlists';

    public function profile(){
        return $this->belongsTo('App\profile','username','username');
    }
    public function item(){
        return $this->belongsTo('App\items','item_id','item_id');
    }

}
