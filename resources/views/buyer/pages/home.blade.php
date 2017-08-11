@extends('master')

@section('top resources')
<!-- Morris Charts CSS -->
<link href="{{ url('/core/plugins/morris/morris.css') }}" rel="stylesheet">
@stop

@section('body')
<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Dashboard <small>Statistics Overview</small>
        </h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $my_orders_count }}</div>
                        <div>My Orders</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/user/orders') }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $my_wishlists }}</div>
                        <div>My Wish Lists</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/user/wishlist') }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->


@stop

@section('bottom resources')
<script type="application/javascript" src="{{ url('/core/js/pages/home.js') }}"></script>
<!-- highcharts -->
<script type="application/javascript" src="{{ url('/core/plugins/highcharts/js/highcharts.js') }}"></script>
@stop