<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

//use facades redis
use Illuminate\Support\Facades\Redis;
use App\role;
use App\notifications;
use App\roles_id;

class NotificationsController extends Controller
{
    public function create_notification(Request $request){
    	//get the username to those who have an admin username
	    $admins = [];
	    foreach(roles_id::where('role_id',role::where('role_name','admin')->first()->role_id)->get() as $a){

	        array_push($admins, $a->username);

	        //save to notification table
	        $not = new notifications();
	        $not->username= $a->username;
	        $not->sender = 'system';
	        $not->contents = '<b>A new user</b> has been created his/her account';
	        $not->save();
	    }

	    //send notification
	    $data = [
	        'event' => 'notification',
	        'contents' => '<b>A new user</b> has created his/her account.',
	        'to' => $admins,
	        'type' => 'notification',
	        'sender' => 'system',
	    ];

	    Redis::publish('notifications',json_encode($data));
    }

    public function clear_notification(Request $request){
    	foreach(notifications::where('username',$request->id)->get() as $n){
    		$n->delete();
    	}

    	return response()->json([ 'success' => true ]);
    }
}
