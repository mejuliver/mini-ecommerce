@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/css/pages/profile.css') }}" type="text/css">
<link rel="stylesheet" href="{{ url('/core/plugins/imagehover/imagehover.min.css') }}" type="text/css">
@stop

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-12">
        <div class="radius-3px full-width bg-blue" id="user-banner" style="height:230px;@if($user_info->banner||$user_info->banner!==''){!!'background-image:url('.url('/app/system/user/'.$user_info->username.'/banner/'.$user_info->banner).');'!!}@endif{{'background-size:cover;background-position:top center;background-repeat:no-repeat;'}}">
            <button class="btn btn-default margin-10px font-size12px c-white" style="background:none;border-color:#ffffff;" id="browse-banner-button">Change banner</button>
            <form action="{{ url('/app/system/user/banner-pic') }}" class="thehide ejex-form" method="post" data-type="json" data-onsuccess="banner_pic" enctype="multipart/form-data">
                <input type="file" class="thehide" id="browse-banner" name="image">
            </form>
            <div style="position:absolute;bottom:0px;z-index:98;margin-left:195px;">
                <h3 class"c-white">{{ $user_info->first_name.' '.$user_info->middle_name.' '.$user_info->last_name }}</h3>
                 <h5 class="text-transform-capitalize">
                    {{ implode(',',$roles) }}
                </h5>
            </div>
        </div>
        <div style="position:absolute;z-index:99;margin-top:-80px;margin-left:25px;" id="user-image">
            <figure class="imghvr-push-down radius-circle" style="width:150px;height:150px;">
                @if(count(array_intersect($roles,['admin']))>0)
                    @if($user_info->img)
                        <img id="user-pic" src="{{url('/app/system/user/'.$user_info->username.'/profile/'.$user_info->img)}}" class="radius-circle" style="width:150px;height:150px;" alt="Dinhi">
                    @else
                        <img id="user-pic" src="{{ url('/core/media/images/no_img.jpg') }}" class="radius-circle" style="width:150px;height:150px;" alt="Dinhi">
                    @endif
                @else
                    @if($user_info->temp_img)
                        <img id="user-pic" src="{{ url('/core/media/images/no_img.jpg') }}" class="radius-circle" style="width:150px;height:150px;" alt="Dinhi">
                    @elseif(!$user_info->temp_img&&!$user_info->img)
                        <img id="user-pic" src="{{ url('/core/media/images/no_img.jpg') }}" class="radius-circle" style="width:150px;height:150px;" alt="Dinhi">
                    @else
                        <img id="user-pic" src="{{url('/app/system/user/'.$user_info->username.'/profile/'.$user_info->img)}}" class="radius-circle" style="width:150px;height:150px;" alt="Dinhi">
                    @endif
                @endif 
                <figcaption style="font-style:italic;font-size:13px;text-align:center;padding-top:45px;">
                    @if(count(array_intersect($roles,['admin']))>0)
                        Hi! this is you, click to change your photo
                    @else
                        @if($user_info->temp_img)
                            <span class="c-red">Pending for approval, click to change.</span>
                        @else
                            Hi! this is you, click to change your photo
                        @endif
                    @endif 
                </figcaption>
                <a href="#" id="browse-pic-button"></a>
                <form action="{{ url('/app/system/user/profile-pic') }}" class="thehide ejex-form" method="post" data-type="json" data-onsuccess="profile_pic" enctype="multipart/form-data">
                    <input type="file" name="image" class="thehide" id="browse-pic">
                </form>
            </figure>
        </div>
    </section>
   <div class="container">
        <section class="col-lg-12">
            <form action="{{ url('/app/system/user/profile/update') }}" class="ejex-form" style="margin-top:100px;" method="post" data-type="json" data-onsuccess="profile_update" data-custom-message="Profile has been updated successfully" id="profile-info-form">
                <fieldset>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>First Name</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="first_name" value="{{ $user_info->first_name }}" placeholder="First name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Middle Name</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="middle_name" value="{{ $user_info->middle_name }}" placeholder="Middle name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Last Name</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="last_name" value="{{ $user_info->last_name }}" placeholder="Last name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Age</label>
                        </div>
                        <div class="col-md-10">
                            <select name="age" class="form-control" value="{{ $user_info->age }}">
                                <option disabled selected>Select age</option>
                                <?php for($i=18;$i<70;$i++){ ?>
                                <option value="{{ $i }}" @if($user_info->age===$i){{'selected'}}@endif>{{ $i }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Address</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="address" class="form-control" placeholder="Address">{{ $user_info->address }}</textarea>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Email</label>
                        </div>
                        <div class="col-md-10">
                            <input type="email" name="email" value="{{ $user_info->email }}" placeholder="Email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Phone</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="phone" value="{{ $user_info->phone }}" placeholder="Phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group overflow-auto" style="margin-top:27px;">
                        <div class="col-md-2">
                            <label>Username</label>
                        </div>
                        <div class="col-md-10">
                            <div class="j-text">{{ $user_info->username }}</div>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Password</label>
                        </div>
                        <div class="col-md-10">
                            <input type="password" name="password" value="{{ $user_info->user->real_password }}" placeholder="Password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Confirm Password</label>
                        </div>
                        <div class="col-md-10">
                            <input type="password" name="password_confirmation" value="" placeholder="Confirm password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group overflow-auto">
                        <div class="col-md-2">
                            <label>Password Visibility</label>
                        </div>
                        <div class="col-md-10">
                            <input type="checkbox" id="password-visibility">
                        </div>
                    </div>
                </fieldset>
            </form>
            <div class="button-holder">
                <div class="display-row">
                    <div class="display-cell {{--padding-right5px--}}">
                        <button class="btn btn-success" id="submit-update-form">
                            <div class="display-table">
                                <div class="display-row">
                                    <div class="display-cell padding-right5px">
                                        <i class="fa fa-check"></i>
                                    </div>
                                    <div class="display-cell">
                                        Update
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    {{-- <div class="display-cell">
                        <button class="btn btn-danger" id="delete-sale-item-button">
                            <div class="display-table">
                                <div class="display-row">
                                    <div class="display-cell padding-right5px">
                                        <i class="fa fa-trash"></i>
                                    </div>
                                    <div class="display-cell">
                                        Delete
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div> --}}
                </div>
            </div>
            
        </section>
   </div>
</div>
<!-- /.row -->
@stop


@section('bottom resources')
<script src="{{ url('/core/js/pages/profile/profile.js') }}"></script>
@stop