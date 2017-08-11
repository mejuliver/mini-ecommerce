<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_images extends Model
{

	protected $table = 'item_images';

    public function item(){
        return $this->belongsTo('App\items','item_id','item_id');
    }
    public function profile(){
        return $this->belongsTo('App\profile','username','username');
    }

}
