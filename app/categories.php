<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'cat_id';

    public function item_category(){
    	return $this->belongsTo('App\item_categories','cat_id','cat_id');
    }

}
