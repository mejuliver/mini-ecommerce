@extends('master')

@section('body')

@include('_breadcrumbs')
<div class="row">
    <form action="{{ url('/app/system/sale-items/item/create') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="create_item" data-custom-message="Successfully created a new sale item" enctype="multipart/form-data">
        <fieldset>
            <section class="col-lg-9">
                <div class="form-group">
                    <input type="text" name="item_name" class="form-control" placeholder="Item name" required>
                </div>
                <div class="form-group">
                    <textarea name="item_desc" class="form-control" style="height:300px;" placeholder="Item name"></textarea>
                </div>
                <div class="form-group overflow-auto">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Item Status</label>
                            <select name="status" class="form-control" required>
                                <option disabled selected>Select item status</option>
                                <option value="sale">Sale</option>
                                <option value="not on sale">Not on sale</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-control" required>
                                <option disabled selected>Select payment type</option>
                                @foreach($payment_types as $p)
                                <option value="{{ $p->payment_id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" class="form-control number" name="quantity" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PRICE</label>
                            <input type="text" class="form-control currency" name="price" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>DISCOUNTED PRICE <span class="c-gray font-size10px">Optional</span></label>
                            <input type="text" class="form-control currency" name="discounted_price" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="margin-top10px">
                         <div class="display-table">
                             <div class="display-row">
                                 <div class="display-cell padding-right5px">
                                    <input type="checkbox" name="allow_reviews" checked>
                                 </div>
                                 <div class="display-cell">
                                    Allow reviews
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </section>
            <section class="col-lg-3">
                <div class="overflow_auto">
                    <h5>Tags:</h5>
                    <div id="tag-input" class="form-control" style="min-height:34px;height:auto;overflow-auto;">
                        <input type="text" placeholder="Add tag" style="width:35px;">
                        <span class="thehide font-size9px" style="width:35px;"></span>
                    </div>
                    <div id="tag-suggestion" class="overflow-y"></div>
                </div>
                <div class="overflow_auto margin-top10px">
                    <h5>Product picture:</h5>
                     <input type="file" class="font-size13px" name="product_picture" required>
                </div>
                <div class="overflow_auto margin-top10px">
                     <h5>Categories</h5>
                </div>
                <div class="overflow-auto" id="categories-holder">
                    @foreach($categories as $cat)
                    <a href="#" class="cat radius-15px" data-toggle="tooltip" title="{{ $cat->cat_desc }}" data-cat-id="{{ $cat->cat_id }}">{{ $cat->cat_name }}</a>
                    @endforeach
                </div>
                <div style="margin-top:70px;">
                    <button class="btn btn-success" style="width:100%;padding:10px 0px;">
                        <div class="display-table center">
                            <div class="display-row">
                                <div class="display-cell padding-right5px">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="display-cell">
                                    Create
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
            </section>
        </fieldset>
    </form>
</div>
<!-- /.row -->

@stop


@section('bottom resources')
<script src="{{ url('/core/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ url('/core/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ url('/core/js/pages/sale_items/sale_items_create_item.js') }}"></script>
@stop