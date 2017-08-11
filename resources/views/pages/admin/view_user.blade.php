@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/css/pages/admin_view_user.css') }}" type="text/css">
<link rel="stylesheet" href="{{ url('/core/plugins/imagehover/imagehover.min.css') }}" type="text/css">
@stop

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-12">
        <div class="radius-3px full-width bg-blue" id="user-banner" style="height:230px;@if($users_profile->banner||$users_profile->banner!==''){!!'background-image:url('.url('/app/system/user/'.$users_profile->username.'/banner/'.$users_profile->banner).');'!!}@endif{{'background-size:cover;background-position:top center;background-repeat:no-repeat;'}}">
            <div style="position:absolute;bottom:0px;z-index:98;margin-left:195px;">
                <h3 class"c-white">{{ $users_profile->first_name.' '.$users_profile->middle_name.' '.$users_profile->last_name }}</h3>
                 <h5 class="text-transform-capitalize">
                    {{ implode(',',$roles) }}
                </h5>
            </div>
        </div>
        <div style="position:absolute;z-index:99;margin-top:-80px;margin-left:25px;">
            <figure class="imghvr-push-down radius-circle" style="width:150px;height:150px;">
                <img id="user-pic" src="@if(!$users_profile->img||$users_profile->img===''){{'{{ url('/core/media/images/no_img.jpg') }}@else{{url('/app/system/user/'.$users_profile->username.'/profile/'.$users_profile->img)}}@endif" class="radius-circle" style="width:150px;height:150px;">
                <figcaption style="font-style:italic;font-size:13px;text-align:center;padding-top:45px;">
                    Hi! its me!
                </figcaption>
            </figure>
        </div>
    </section>
   <div class="container">
        <section class="col-lg-12" style="margin-top:100px;">
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Status:</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->status }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>First Name</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->first_name }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Middle Name</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->middle_name }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Last Name</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->last_name }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Age</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->age }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Address</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->address }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Email</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->email }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto" style="margin-top:27px;">
                <div class="col-md-2">
                    <label>Username</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->username }}</div>
                </div>
            </div>
            <div class="form-group overflow-auto">
                <div class="col-md-2">
                    <label>Password</label>
                </div>
                <div class="col-md-10">
                    <div class="j-text">{{ $users_profile->user->real_password }}</div>
                </div>
            </div>
        </section>
        <section class="col-lg-6" style="margin-top:70px;">
            <div class="display-table margin-bottom15px">
                <div class="display-row">
                    <div class="display-cell">
                        <div class="hexagon" id="items-counter">
                            <div class="text-align-center font-700 c-white margin-top3px">{{ $items }}<br>Items</div>
                        </div>
                    </div>
                    <div class="display-cell">
                        <div class="hexagon" id="reviews-counter">
                            <div class="text-align-center font-700 c-white">
                                <div class="display-table center">
                                    <?php
                                    $d = $reviews;
                                    for($i=0;$d>$i;$i++){ ?>
                                    <div class="star-rating rated">
                                         <span></span>
                                    </div>
                                <?php } ?>
                                <?php
                                    $d = 5-$d;
                                    for($i=0;$d>$i;$i++){ ?>
                                    <div class="star-rating unrated">
                                         <span></span>
                                    </div>
                                <?php } ?>
                                </div>
                                <span>Reviews</span>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if(count($tags)>0)
        <section class="col-lg-6" style="margin-top:70px;">
            <h5>Tags</h5>
            <div class="overflow-auto">
                @foreach($tags as $i)
                    <a href="#" class="tag">{{ $i->tag_name }}</a>
                @endforeach
            </div>
        </section>
        @endif
   </div>
</div>
<!-- /.row -->
@stop

