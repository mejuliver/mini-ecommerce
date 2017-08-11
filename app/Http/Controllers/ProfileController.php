<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

//storage and file facades
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;    
//validation facades
use Illuminate\Support\Facades\Validator;

//use models
use App\profile;
use App\User;
use App\permissions_id;
use App\wishlists;

use App\roles_id;
use App\role;
use App\notifications;

//use facades redis
use Illuminate\Support\Facades\Redis;

class ProfileController extends Controller
{
    public function __construct(){
        //check authentication first
        if(auth::guard('dashboard')->check()):
            $this->middleware('dashboard');
        elseif(auth::guard('user')->check()):
            $this->middleware('user');
        endif;
    }
    function user_info(){
        //check guard
        if(auth::guard('dashboard')->check()):
            //return username
            return auth::guard('dashboard')->user();
        elseif(auth::guard('user')->check()):
            return auth::guard('user')->user();
        endif;
        
    }
    function current_user_info(){
        return profile::where('username',$this->user_info()->username)->with('user')->with('roles_id.role')->with('permissions_id.permission')->with('tags')->first();
    }

    function perms(){
        //pass the permission
        $perms = permissions_id::where('username',$this->user_info()->username)->with('permission')->get();

        $perms_array = [];

        foreach($perms as $p):
            array_push($perms_array,$p->permission->perm_name);
        endforeach;

        return $perms_array;
    }

    function roles(){
        $roles = roles_id::where('username',$this->user_info()->username)->with('role')->get();

        $roles_array = [];

        foreach($roles as $r):
            array_push($roles_array,$r->role->role_name);
        endforeach;

        return $roles_array;
    }


    function notifications(){
        if(Auth::guard('dashboard')->check()):
            return notifications::where('sender','!=',$this->current_user_info()->username)->where('username',$this->current_user_info()->username)->get();
        else:
            return false;
        endif;
    }
    public function index(){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Profile';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/user/profile'),
            'name' => 'Profile'   
            ]
        ];
        return view('pages.profile',compact('current_page','roles','perms','notifications','user_info','breadcrumbs'));
    }

    //profile pic
    public function profile_pic(Request $request){
    	$error = array();
        $file = $request->file('image');
        //check if there's a file image
        if($file){ //if there's an image then
                $validator = Validator::make($request->all(),[
                    'image.*' => 'image|image_size:115-400,115-400'
                ]);
                if($validator->fails()){
                    foreach($validator->errors()->getMessages() as $validationErrors):
                        if (is_array($validationErrors)) {
                            foreach($validationErrors as $validationError):
                                $error[] = $validationError;
                            endforeach;
                        } else {
                            $error[] = $validationErrors;
                        }
                    endforeach;
                }//end if validation fails
            if(count($error)===0){
                unset($error);

                $extension = $file->getClientOriginalExtension();
                $file_name = $file->getClientOriginalName();

                $p = profile::where('username',$this->user_info()->username)->first();

                //check if there's already an image or not, if there is then delete it
                if(count(array_intersect($this->roles(), ['admin']))>0){
                    foreach(Storage::files($this->current_user_info()->username.'/profile/') as $d){
                        Storage::delete($d);
                    }
                    //store the image
                    Storage::put($this->current_user_info()->username.'/profile/'.$file_name, File::get($file));

                    $p->img = $file_name;
                    $p->update();

                    return response()->json([ 'success' => true,  'image' => url('/app/system/user/'.$this->user_info()->username.'/profile/'.$p->img) ]);
                }else{
                    foreach(Storage::files($this->current_user_info()->username.'/profile/temp_img') as $d){
                        Storage::delete($d);
                    }
                    //store the image
                    Storage::put($this->current_user_info()->username.'/profile/temp_img/'.$file_name, File::get($file));
                    
                    $p->temp_img = $file_name;
                    $p->update();

                    //get the username to those who have an admin username
                    $admins = [];
                    foreach(roles_id::where('role_id',role::where('role_name','admin')->first()->role_id)->get() as $a){

                        array_push($admins, $a->username);

                        //save to notification table
                        $not = new notifications();
                        $not->username= $a->username;
                        $not->sender = 'system';
                        $not->contents = '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has uploaded new primary picture';
                        $not->save();
                    }

                    //send notification
                    $data = [
                        'event' => 'notification',
                        'contents' => '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has uploaded new primary picture',
                        'to' => $admins,
                        'type' => 'notification',
                        'sender' => 'system',
                    ];
             
                    Redis::publish('notifications',json_encode($data));

                    return response()->json([ 'success' => true,  'image' => '/core/media/images/no_img.jpg', 'status' => 'pending' ]);
                }
                
            }else{
                $str = '<ul class="c-red padding-zero margin-zero menu">';
                foreach($error as $e ){
                    $str.= '<li>'.$e.'</li>';
                }
                $str.='</ul>';

                return response()->json([ 'success' => false,  'message' => $str ]);
            }
        }else{ //else if there's no image file
            return response()->json([ 'success' => false,  'message' => "Image is required" ]);
        }//end checking if file exist
    }
    //banner pic
    public function banner_pic(Request $request){
    	$error = array();
        $file = $request->file('image');
        //check if there's a file image
        if($file){ //if there's an image then
                $validator = Validator::make($request->all(),[
                    'image.*' => 'image|image_size:115-3000,115-3000'
                ]);
                if($validator->fails()){
                    foreach($validator->errors()->getMessages() as $validationErrors):
                        if (is_array($validationErrors)) {
                            foreach($validationErrors as $validationError):
                                $error[] = $validationError;
                            endforeach;
                        } else {
                            $error[] = $validationErrors;
                        }
                    endforeach;
                }//end if validation fails
            if(count($error)===0){
                unset($error);

                $extension = $file->getClientOriginalExtension();
                $file_name = $file->getClientOriginalName();

				$p = profile::where('username',$this->user_info()->username)->first();
                foreach(Storage::files($this->current_user_info()->username.'/banner') as $d){
                    Storage::delete($d);
                }
				//store the image
                Storage::put($this->current_user_info()->username.'/banner/'.$file_name, File::get($file));
                
                $p->banner = $file_name;
                $p->update();

                return response()->json([ 'success' => true,  'image' => url('/app/system/user/'.$this->user_info()->username.'/banner/'.$p->banner) ]);
            }else{
                $str = '<ul class="c-red padding-zero margin-zero menu">';
                foreach($error as $e ){
                    $str.= '<li>'.$e.'</li>';
                }
                $str.='</ul>';

                return response()->json([ 'success' => false,  'message' => $str ]);
            }
        }else{ //else if there's no image file
            return response()->json([ 'success' => false,  'message' => "Image is required" ]);
        }//end checking if file exist
    }
    //update profile
    public function update_profile(Request $request){
    	$error = array();
    	$validator = Validator::make($request->all(),[
            'first_name' => 'string|max:255|required',
            'last_name' => 'string|max:255|required',
            'email' => 'email|required',

        ]);
        if($validator->fails()){
            foreach($validator->errors()->getMessages() as $validationErrors):
                if (is_array($validationErrors)) {
                    foreach($validationErrors as $validationError):
                        $error[] = $validationError;
                    endforeach;
                } else {
                    $error[] = $validationErrors;
                }
            endforeach;
        }//end if validation fails
        //check if password has been touch
    	if($request->password!==''&&$this->user_info()->real_password!==$request->password){
    		if($request->password!==$request->password_confirmation){
    			$error[] = 'Your password confirmation does not match to your password!';
    		}
    	}
	    if(count($error)===0){
	    	$p = profile::where('username',$this->user_info()->username)->first();
	    	$p->first_name = $request->first_name;
	    	$p->middle_name = $request->middle_name;
	    	$p->last_name = $request->last_name;
	    	$p->age = $request->age;
	    	$p->email = $request->email;
            $p->phone = $request->phone;
	    	$p->update(); //update profile

	    	//check if account has been touch
	    	if($request->password!==''&&$this->user_info()->real_password!==$request->password){
	    		$u = User::where('username',$this->user_info()->username)->first();
	    		$u->password = bcrypt($request->password);
	    		$u->real_password = $request->password_confirmation;
	    		$u->update(); //update account
	    	}

	    	return response()->json([ 'success' => true ]);

	    }else{
	    	$str = '<ul class="c-red padding-zero margin-zero menu">';
            foreach($error as $e ){
                $str.= '<li>'.$e.'</li>';
            }
            $str.='</ul>';

            return response()->json([ 'success' => false,  'message' => $str ]);
	    }
	}
    public function wishlists(){
        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Profile';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/wish-list'),
            'name' => 'wishlists'   
            ]
        ];
        //get all the wishlist
        $wishlists = wishlists::where('username',$this->user_info()->username)->with([ 'item' => function($query){
            $query->where('visibility','sale');
        }
        ])->get();

        return view('pages.wishlists',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','wishlists'));
    }
    public function wishlists_delete(Request $request){
        //delete wishlist
        wishlists::find($request->id)->delete();

        return response()->json([ 'success' => true, 'id' => $request->id ]);
    }
}
