<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    protected $table = "tags";
    protected $primaryKey = "tag_id";

    public function tag_ids(){
    	return $this->belongsTo('App\item_tags','tag_id');
    }
}
