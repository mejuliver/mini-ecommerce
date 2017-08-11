@extends('master')

@section('top resources')
<!-- Morris Charts CSS -->
<link href="{{ url('/core/plugins/morris/morris.css') }}" rel="stylesheet">
@stop

@section('body')
@if($user_info->status!=='pending')
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
    @if($admin===true)
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $sale_items_count }}</div>
                        <div>Sale items</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/admin/sale-items') }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $sellers_count }}</div>
                        <div>Sellers</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/admin/user/sellers') }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $buyers_count }}</div>
                        <div>Buyers</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/admin/user/buyers') }}">
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
                        <i class="fa fa-cubes fa-5x" aria-hidden="true"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $orders_count }}</div>
                        <div>Orders</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/admin/sale-items/orders') }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @else
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $sale_items_count }}</div>
                        <div>My Items</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/admin/sale-items') }}">
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
                        <i class="fa fa-cubes fa-5x" aria-hidden="true"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $orders_count }}</div>
                        <div>My Item's Orders</div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/app/system/orders') }}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading display-table full-width">
                <h3 class="panel-title padding-zero display-table align-left margin-top10px"><i class="fa fa-bar-chart-o fa-fw"></i>Orders Chart</h3>
                <div class="display-table align-right">
                    <div class="display-table align-left j-menu margin-right5px">
                        <ul class="j-menu-nav margin-zero padding-zero list-style-none">
                            <li>
                                <a href="#" class="j-parent btn btn-default" data-has-submenu="yes">
                                    <div class="display-table">
                                        <div class="display-row">
                                            <div class="display-cell padding-right5px"><i class="fa fa-calendar-o" aria-hidden="true"></i></div>
                                            <div class="display-cell padding-right10px">Date Filter</div>
                                            <div class="display-cell"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="j-menu-dp-container list-style-none thehide bg-white padding-15px radius-3px shadow-z-1">
                                    <li>
                                        <form action="{{ url('/app/system/admin/orders-chart/date-filter') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="refresh_orders_chart">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label>Date From:</label>
                                                    <input type="text" name="from" class="datepicker form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Date To:</label>
                                                    <input type="text" name="to" class="datepicker form-control" required>
                                                </div>
                                            </fieldset>
                                            <div class="button-holder">
                                                <button class="btn btn-success">FILTER</button>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <form action="{{ url('/app/system/admin/orders-chart') }}" class="display-table align-left ejex-form" method="post" data-onsuccess="refresh_orders_chart">
                        <button class="btn btn-default">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="panel-body" style="height:320px;">
                <div id="orders-chart" class="chart" style="height:280px;">Loading orders chat...</div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

{{--<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right fa-fw"></i>Sales Chart</h3>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Tasks Panel</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a href="#" class="list-group-item">
                        <span class="badge">just now</span>
                        <i class="fa fa-fw fa-calendar"></i> Calendar updated
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">4 minutes ago</span>
                        <i class="fa fa-fw fa-comment"></i> Commented on a post
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">23 minutes ago</span>
                        <i class="fa fa-fw fa-truck"></i> Order 392 shipped
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">46 minutes ago</span>
                        <i class="fa fa-fw fa-money"></i> Invoice 653 has been paid
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">1 hour ago</span>
                        <i class="fa fa-fw fa-user"></i> A new user has been added
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">2 hours ago</span>
                        <i class="fa fa-fw fa-check"></i> Completed task: "pick up dry cleaning"
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">yesterday</span>
                        <i class="fa fa-fw fa-globe"></i> Saved the world
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">two days ago</span>
                        <i class="fa fa-fw fa-check"></i> Completed task: "fix error on sales page"
                    </a>
                </div>
                <div class="text-right">
                    <a href="#">View All Activity <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Transactions Panel</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Amount (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>3326</td>
                                <td>10/21/2013</td>
                                <td>3:29 PM</td>
                                <td>$321.33</td>
                            </tr>
                            <tr>
                                <td>3325</td>
                                <td>10/21/2013</td>
                                <td>3:20 PM</td>
                                <td>$234.34</td>
                            </tr>
                            <tr>
                                <td>3324</td>
                                <td>10/21/2013</td>
                                <td>3:03 PM</td>
                                <td>$724.17</td>
                            </tr>
                            <tr>
                                <td>3323</td>
                                <td>10/21/2013</td>
                                <td>3:00 PM</td>
                                <td>$23.71</td>
                            </tr>
                            <tr>
                                <td>3322</td>
                                <td>10/21/2013</td>
                                <td>2:49 PM</td>
                                <td>$8345.23</td>
                            </tr>
                            <tr>
                                <td>3321</td>
                                <td>10/21/2013</td>
                                <td>2:23 PM</td>
                                <td>$245.12</td>
                            </tr>
                            <tr>
                                <td>3320</td>
                                <td>10/21/2013</td>
                                <td>2:15 PM</td>
                                <td>$5663.54</td>
                            </tr>
                            <tr>
                                <td>3319</td>
                                <td>10/21/2013</td>
                                <td>2:13 PM</td>
                                <td>$943.45</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="#">View All Transactions <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row --> --}}
@else
<div class="row">
    <div class="col-lg-12">
        <div class="font-size13px alert alert-info" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-times" aria-hidden="true"></i></a>
            <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px">
                <tr>
                    <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-info-circle" aria-hidden="true"></i></td>
                    <td class="font-size13px text-align-left">Your account is not active yet.</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endif
@stop

@section('bottom resources')
<script type="application/javascript" src="{{ url('/core/js/pages/home.js') }}"></script>
<!-- highcharts -->
<script type="application/javascript" src="{{ url('/core/plugins/highcharts/js/highcharts.js') }}"></script>
@stop