@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ url('/core/css/pages/admin_sale_items.css') }}" type="text/css">
@stop

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-12">
    @if(session('message'))
        <div class="font-size13px alert alert-success" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-times" aria-hidden="true"></i></a>
            <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px">
                <tr>
                    <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-check-circle" aria-hidden="true"></i></td>
                    <td class="font-size13px text-align-left">{{ session('message') }}</td>
                </tr>
            </table>
        </div>
        @endif
        <div class="display-table full-width margin-bottom10px">
            <div class="display-table align-left j-menu">
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
                                <form action="{{ url('/app/system/admin/sale-items/items/date-filter') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="reload_sale_items">
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
            <div class="display-table align-right">
                <form action="{{ url('/app/system/admin/sale-items/items/reload') }}" class="ejex-form display-table align-left margin-right5px" method="post" data-type="json" data-onsuccess="reload_sale_items">
                    <button class="btn btn-default">
                        <div class="display-table">
                            <div class="display-row">
                                <div class="display-cell padding-right5px"><i class="fa fa-refresh" aria-hidden="true"></i></div>
                                <div class="display-cell">Reload Sale Items</div>
                            </div>
                        </div>
                    </button>
                </form>
                <div class="display-table align-left">
                    <a href="{{ url('/app/system/sale-items/item/create') }}" class="btn btn-default">
                        <div class="display-table">
                            <div class="display-row">
                                <div class="display-cell padding-right5px"><i class="fa fa-plus" aria-hidden="true"></i></div>
                                <div class="display-cell">Create Item</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <table class="full-width table table-responsive font-size13px table-th-td-align-left dtable table-vertical-align-middle" id="sale-items-table" data-sort-column="3" data-sort-type="asc">
            <thead>
                <tr>
                    <th>ITEM NAME</th>
                    <th>ITEM PICTURES</th>
                    <th>ITEM REVIEWS</th>
                    <th class="thehide"></th>
                    <th>PRICE</th>
                    <th>ORDERS</th>
                    <th>QUANTITY</th>
                    <th>CREATED AT</th>
                    <th></th>
                </tr>   
            </thead>
            <tbody>
                @foreach($items as $i)
                <tr data-id="{{ $i->item_id }}">
                    <td>
                        <a href="{{ url('/app/system/admin/sale-items/item/'.$i->item_id) }}">{{ $i->item_name }}</a>
                    </td>
                    <td>{{ count($i->item_images).' pics' }}</td>
                    <td>
                        {{ count($i->item_review) }} reviews
                    </td>
                    <td class="thehide">@if($i->status==="pending"){{'a'}}@else{{'b'}}@endif</td>
                    <td>
                        @if($i->discounted)
                        <span style="text-decoration:line-through;">{{ $i->price }}</span>
                        <span>{{ $i->discounted }}</span>
                        @else
                        {{ $i->price }}
                        @endif
                    </td>
                    <td>
                        @if($i->status!=='pending')
                        @if($i->orders>0)
                        <form action="{{ url('/app/system/admin/sale-items/item/view-orders') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="view_orders">
                            <input type="hidden" name="id" value="{{ $i->item_id }}">
                            <div class="display-table center">
                                <div class="display-row">
                                    <div class="display-cell padding-right7px">
                                        <button class="btn btn-info" data-toggle="tooltip" title="View orders" style="font-size:10px;padding:5px 8px;">
                                            View Orders
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @else
                        0 Orders
                        @endif
                        @else
                        <i class="fa fa-exclamation-circle c-red" aria-hidden="true" data-toggle="tooltip" title="Pending item."></i>
                        @endif
                    </td>
                    <td>{{ $i->quantity }}</td>
                    <td>{{ date('M d, Y h:m A',strtotime($i->created_at)) }}</td>
                    <td>
                        @if($i->status==='pending')
                        <div class="display-table center">
                            <div class="display-row">
                                <div class="display-cell padding-right7px">
                                    <form action="{{ url('/app/system/admin/sale-items/item/approve') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="approve_item">
                                        <input type="hidden" name="id" value="{{ $i->item_id }}">
                                        <button class="btn btn-success" data-toggle="tooltip" title="Approve item" style="font-size:10px;padding:5px 8px;">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="display-cell">
                                    <form action="{{ url('/app/system/admin/sale-items/item/reject') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="reject_item">
                                        <input type="hidden" name="id" value="{{ $i->item_id }}">
                                        <button class="btn btn-danger" data-toggle="tooltip" title="Reject item" style="font-size:10px;padding:5px 8px;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="display-table center c-megamitch">
                            <div class="display-row">
                                <div class="display-cell padding-right7px">
                                <i class="fa fa-check"></i>
                                </div>
                                <div class="display-cell">
                                    Approved
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
<!-- /.row -->
<div class="thehide" id="view-order-container">
    <div class="overflow-auto margin-bottom20px" id="order-status">
        <div class="order-status" id="total_orders">
            <div class="order-status-counter"></div>
            <div class="order-status-text">Total Orders</div>
        </div>
        <div class="order-status" id="pending_orders">
            <div class="order-status-counter"></div>
            <div class="order-status-text">Pending Orders</div>
        </div>
        <div class="order-status" id="in_process_orders">
            <div class="order-status-counter"></div>
            <div class="order-status-text">In Process Orders</div>
        </div>
        <div class="order-status" id="completed_orders">
            <div class="order-status-counter"></div>
            <div class="order-status-text">Completed Orders</div>
        </div>
        <div class="order-status" id="error_orders">
            <div class="order-status-counter"></div>
            <div class="order-status-text">Error Orders</div>
        </div>
    </div>
    <table class="full-width table table-hover table-vertical-align-middle table-th-td-align-left font-size13px" data-sort-column="1" data-sort-type="asc">
        <thead>
            <tr>
                <th>ITEM ID</th>
                <th class="thehide"></th>
                <th>ORDER NAME</th>
                <th>ORDERER</th>
                <th>ADDRESS</th>
                <th>PAYMENT TYPE</th>
                <th>STATUS</th>
                <th>NOTE</th>
                <th>ORDER DATE</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div class="thehide" id="view-order-items-container">
    <table class="full-width table table-hover table-vertical-align-middle table-th-td-align-left font-size13px" data-sort-column="1" data-sort-type="asc">
        <thead>
            <tr>
                <th>ITEM NAME</th>
                <th class="thehide"></th>
                <th>QUANTITY</th>
                <th>STATUS</th>
                <th>NOTE</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@stop

@section('bottom resources')
<script src="{{ url('/core/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ url('/core/js/pages/admin/sale_items.js') }}"></script>
@stop