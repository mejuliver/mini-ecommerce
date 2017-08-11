@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
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
                                <form action="{{ url('/app/system/sale-items/items/date-filter') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="reload_sale_items">
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
                <form action="{{ url('/app/system/sale-items/items/reload') }}" class="ejex-form display-table align-left margin-right5px" method="post" data-type="json" data-onsuccess="reload_sale_items">
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
                    <th>QUANTITY</th>
                    <th>CREATED AT</th>
                </tr>   
            </thead>
            <tbody>
                @foreach($items as $i)
                <tr>
                    <td>
                        <a href="{{ url('/app/system/sale-items/item/'.$i->item_id) }}">{{ $i->item_name }}</a>
                    </td>
                    <td>{{ count($i->item_images).' pics' }}</td>
                    <td>
                        {{ count($i->item_review) }} reviews
                    </td>
                    <td class="thehide">{{ $i->created_at }}</td>
                    <td>{{ $i->price }}</td>
                    <td>{{ $i->quantity }}</td>
                    <td>{{ date('M d, Y h:m A',strtotime($i->created_at)) }}</td>
                    {{--
                    <td>
                        <div class="display-table center">
                            <div class="display-row">
                                <div class="display-cell padding-right7px">
                                    <button class="btn btn-info view-item" data-toggle="tooltip" title="View this item" style="font-size:10px;padding:5px 8px;">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="display-cell padding-right7px">
                                    <button class="btn btn-success edit-item" data-toggle="tooltip" title="Edit this item" style="font-size:10px;padding:5px 8px;">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="display-cell">
                                    <button class="btn btn-danger delete-item" data-toggle="tooltip" title="Delete this item" style="font-size:10px;padding:5px 8px;">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                    --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
<!-- /.row -->
@stop

@section('bottom resources')
<script src="{{ url('/core/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ url('/core/js/pages/sale_items/sale_items.js') }}"></script>
@stop