@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
@stop

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-12">
        <table class="full-width table table-responsive font-size13px table-th-td-align-left dtable table-vertical-align-middle" id="wishlist-table" data-sort-column="4" data-sort-type="asc">
            <thead>
                <tr>
                    <th>ITEM NAME</th>
                    <th>ITEM PRICE</th>
                    <th>ITEM QUANTITY</th>
                    <th>STATUS</th>
                    <th class="thehide"></th>
                    <th></th>
                </tr>   
            </thead>
            <tbody>
                @foreach($wishlists as $w)
                <tr data-id="{{ $w->id }}">
                    <td>{{ $w->item->item_name }}</td>
                    <td>@if($w->item->discounted) {{ $w->item->discounted }}@else{{ $w->item->price }}@endif</td>
                    <td>{{ $w->item->quantity }}</td>
                    <td>@if($w->item->quantity>0) {{ $w->item->quantity }} @else <span class="c-red">Out of stock @endif</span></td>
                    <td class="thehide">{{ $w->created_at }}</td>
                    <td>
                        <form action="{{ url('/app/system/wishlists/delete') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="delete_wishlist">
                            <input type="hidden" name="id" value="{{ $w->id }}">
                            <div class="display-table center">
                                <div class="display-row">
                                    <div class="display-cell padding-right7px">
                                        <button class="btn btn-danger" data-toggle="tooltip" title="Delete wishlist" style="font-size:10px;padding:5px 8px;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </td>
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
<script src="{{ url('/core/js/pages/wishlist.js') }}"></script>
@stop