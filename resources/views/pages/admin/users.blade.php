@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
<style>
    .pulse-wave {
        cursor:pointer;
        display: block;
        width: 30px;
        height: 30px;
        margin:0 auto;
        background:none;

        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(252,0,0, 0.4);
        animation: pulse-wave 2s infinite;
    }
    .pulse-wave:hover {
        animation: none;
    }

    @-webkit-keyframes pulse-wave {
      0% {
        -webkit-box-shadow: 0 0 0 0 rgba(252,0,0, 0.4);
      }
      70% {
          -webkit-box-shadow: 0 0 0 10px rgba(252,0,0, 0);
      }
      100% {
          -webkit-box-shadow: 0 0 0 0 rgba(252,0,0, 0);
      }
    }
    @keyframes pulse-wave {
      0% {
        -moz-box-shadow: 0 0 0 0 rgba(252,0,0, 0.4);
        box-shadow: 0 0 0 0 rgba(252,0,0, 0.4);
      }
      70% {
          -moz-box-shadow: 0 0 0 10px rgba(252,0,0, 0);
          box-shadow: 0 0 0 10px rgba(252,0,0, 0);
      }
      100% {
          -moz-box-shadow: 0 0 0 0 rgba(252,0,0, 0);
          box-shadow: 0 0 0 0 rgba(2252,0,0, 0);
      }
    }
</style>
@stop

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-12">
        <table class="table table-hover dtable font-size13px full-width table-th-td-align-left table-vertical-align-middle" data-sort-column="2" data-sort-type="asc" id="users-table">
            <thead>
                <tr>
                    <th></th>
                    <th>USERNAME</th>
                    <th class="thehide">SORTING</th>
                    <th>NAME</th>
                    <th>AGE</th>
                    <th>ADDRESS</th>
                    <th>EMAIL</th>
                    <th>STATUS</th>
                    <th>CREATED DATE</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                @if($user->username!==$user_info->username)
                <tr data-id="{{ $user->username }}">
                    <td>
                        @if($user->temp_img)
                            <div class="pulse-wave pointer new-pic-img-review" data-toggle="tooltip" title="New profile picture, click to review." data-placement="right" data-id="{{ $user->username }}">
                                <img src="/app/system/user/{{$user->username}}/profile/temp/{{$user->temp_img}}" alt="{{ $user->first_name.' '.$user->last_name }}" class="display-block radius-circle" style="width:30px;height:30px;">
                                <span class="fa fa-exclamation-circle c-red animated pulse infinite" style="position:absolute;margin-top:-35px;"></span>
                            </div>
                        @else
                            @if(!$user->img)
                            <img src="{{ url('/core/media/images/no_img.jpg') }}" alt="{{ $user->first_name.' '.$user->last_name }}" class="display-block center radius-circle" style="width:30px;height:30px;">
                            @else 
                            <img src="{{ url('/app/system/user/'.$user->username.'/profile/'.$user->img) }}" alt="{{ $user->first_name.' '.$user->last_name }}" class="display-block center radius-circle" style="width:30px;height:30px;">
                            @endif
                        @endif
                    </td>
                    <td>{{ $user->username }}</td>
                    <td class="thehide">@if($user->status==="pending"){{'a'}}@else{{'b'}}@endif</td>
                    <td>{{ $user->first_name.' '.$user->last_name}}</td>
                    <td>{{ $user->age }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->status==='active')
                        <div class="display-table center c-megamitch">
                            <div class="display-row">
                                <div class="display-cell padding-right5px">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="display-cell">
                                    Active
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="display-table center c-yellow">
                            <div class="display-row">
                                <div class="display-cell padding-right5px">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                </div>
                                <div class="display-cell">
                                    Pending
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                    <td>{{ date('M d, Y h:m A',strtotime($user->created_at)) }}</td>
                    <td>
                        @if($user->status==='pending')
                        <div class="display-table center">
                            <div class="display-row">
                                <div class="display-cell padding-right7px">
                                    <a href="{{ url('/app/system/admin/users/'.$user->username) }}" class="btn btn-info" data-toggle="tooltip" title="View profile" style="font-size:10px;padding:5px 8px;">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="display-cell padding-right7px">
                                    <form action="{{ url('/app/system/admin/users/activate-account') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="activate_account" data-success-function="hide_pokemon" data-custom-message="Account has been activated.">
                                        <input type="hidden" name="id" value="{{ $user->username }}">
                                        <button class="btn btn-success activate-account" data-toggle="tooltip" title="Activate account" style="font-size:10px;padding:5px 8px;">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="display-cell">
                                    <button class="btn btn-danger reject-account" data-id="{{ $user->username }}" data-toggle="tooltip" title="Reject account" style="font-size:10px;padding:5px 8px;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="display-table center">
                           <a href="{{ url('/app/system/admin/users/'.$user->username) }}" class="btn btn-info" data-toggle="tooltip" title="View profile" style="font-size:10px;padding:5px 8px;">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </div>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </section>
</div>

<div class="thehide" id="reject-account-container">
    <form action="{{ url('/app/system/admin/users/reject-account') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="reject_account" data-success-function="hide_pokemon" data-custom-message="Account has been rejected.">
        <fieldset>
            <input type="hidden" name="id">
            <div class="text-align-center font-700 c-red">Are you sure you want to reject this account? Click 'Reject' to reject this account.</div>
        </fieldset>
        <div class="button-holder">
             <button class="btn btn-danger">
                <div class="display-table">
                    <div class="display-row">
                        <div class="display-cell padding-right5px">
                            <i class="fa fa-times"></i>
                        </div>
                        <div class="display-cell">
                            Reject
                        </div>
                    </div>
                </div>
            </button>
        </div>
    </form>
</div>

<div class="thehide" id="new-pic-img-review-container">
    <div id="new-pic-img-review-msg" class="full-width"></div>
    <div class="container-view">
        <div class="overflow-auto">
            <img src="" class="extend">
        </div>
        <div class="button-holder" style="margin-top:30px;">
            <div class="display-table">
                <div class="display-row">
                    <div class="display-cell padding-right7px">
                        <form action="{{ url('/app/system/admin/users/new-img/review/approve') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="new_img_review" data-custom-message="Successfully approved the new image of the user" data-message-place="#notification-dialog #new-pic-img-review-msg" data-success-function="hide_container_view">
                            <input type="hidden" name="id" value="">
                            <button class="btn btn-success">
                                <div class="display-table">
                                    <div class="display-row">
                                        <div class="display-cell padding-right5px">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <div class="display-cell">
                                            Approve
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                    <div class="display-cell">
                        <form action="{{ url('/app/system/admin/users/new-img/review/reject') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="new_img_review" data-custom-message="Successfully rejected the new image of the user" data-message-place="#notification-dialog #new-pic-img-review-msg" data-success-function="hide_container_view">
                            <input type="hidden" name="id" value="">
                            <button class="btn btn-danger">
                                <div class="display-table">
                                    <div class="display-row">
                                        <div class="display-cell padding-right5px">
                                            <i class="fa fa-trash"></i>
                                        </div>
                                        <div class="display-cell">
                                            Reject
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
@stop

@section('bottom resources')    
<script src="{{ url('/core/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ url('/core/js/pages/admin/users.js') }}"></script>
@stop