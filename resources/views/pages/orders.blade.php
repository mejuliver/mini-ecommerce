@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
@stop

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-12">
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
                                <form action="{{ url('/app/system/orders/date-filter') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="reload_orders">
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
                <form action="{{ url('/app/system/orders/reload-orders') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="reload_orders">
                    <button class="btn btn-default">
                        <div class="display-table">
                            <div class="display-row">
                                <div class="display-cell padding-right5px"><i class="fa fa-refresh" aria-hidden="true"></i></div>
                                <div class="display-cell">Reload Orders</div>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
        <table class="full-width table table-responsive font-size13px table-th-td-align-left dtable table-vertical-align-middle" id="orders-table" data-sort-column="5" data-sort-type="asc">
            <thead>
                <tr>
                    <th>ORDER ID</th>
                    <th>ORDER NAME</th>
                    <th>PAYMENT TYPE</th>
                    <th>STATUS</th>
                    <th class="thehide"></th>
                    <th>ITEM ORDERS</th>
                    <th>CREATED AT</th>
                </tr>   
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td>{{ $o->order_id }}</td>
                    <td>{{ $o->order_name }}</td>
                    <td>{{ $o->payment_type }}</td>
                    <td>{{ $o->status }}</td>
                    <td>{{ $o->note }}</td>
                    <td class="thehide">{{ $o->created_at }}</td>
                    <td>
                        <form action="{{ url('/app/system/admin/sale-items/item/view-orders/orders') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="view_orders_order">
                            <input type="hidden" name="order_id" value="{{ $o->order_id }}">
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
                    </td>
                    <td>{{ date('M d, Y h:m A',strtotime($o->created_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
<!-- /.row -->
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
<script src="{{ url('/core/js/pages/order.js') }}"></script>
@stop