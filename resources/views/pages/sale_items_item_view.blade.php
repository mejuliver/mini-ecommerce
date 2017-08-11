@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ url('/core/css/pages/sale_items_item_view.css') }}" type="text/css">
<style>
    .tinymce-body p{margin:0px;padding:0px;}
</style>
@stop

@section('body')

@include('_breadcrumbs')
<div class="row item-view" data-item-id="{{ $item->item_id }}">
    <section class="col-lg-9">
        <div class="c-red margin-bottom10px"><b>@if($item->status==="pending"){{ "This is subject for admin's approval. This will be only visible to the store once approve by admin." }} @endif</b></div>
        <form action="{{ url('/app/system/sale-items/item/update') }}" method="post" class="ejex-form" data-type="json" data-custom-message="Item has been updated!" data-before-send="save_tinymce_textarea" id="item-update-form">
            {{--@if(session('message'))
            <div class="font-size13px alert alert-success" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-times" aria-hidden="true"></i></a>
                <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px">
                    <tr>
                        <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-check-circle" aria-hidden="true"></i></td>
                        <td class="font-size13px text-align-left">{{ session('message') }}</td>
                    </tr>
                </table>
            </div>
            @endif--}}
            <input type="hidden" name="id" value="{{ $item->item_id }}">
            <fieldset>
                <div class="form-group overflow-auto">
                    <input type="text" name="item_name" value="{{ $item->item_name }}" class="form-control" placeholder="Item name" required>
                </div>
                <div class="form-group overflow-auto">
                    <textarea name="item_desc" class="form-control" style="height:300px;" placeholder="Item description">{{ $item->item_desc }}</textarea>
                </div>
                <div class="form-group overflow-auto">
                    <textarea name="details" class="form-control" style="height:300px;" placeholder="Item details">{{ $item->details }}</textarea>
                </div>
                <div class="form-group overflow-auto">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Item Status</label>
                            <select name="status" class="form-control" value="{{ $item->status }}" required>
                                <option disabled>Select item status</option>
                                <option value="sale" {{ $item->status==='sale' ? 'selected' : '' }}>Sale</option>
                                <option value="not on sale" {{ $item->status==='not on sale' ? 'selected' : '' }}>Not on sale</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-control" value="{{ $item->items_payment_type->payment_id!==''||$item->items_payment_type->payment_id!==null||$item->items_payment_type->payment_id!==NULL?$item->items_payment_type->payment_id:'' }}" required>
                                <option disabled {{ $item->items_payment_type->payment_id===''||$item->items_payment_type->payment_id===null||$item->items_payment_type->payment_id===NULL?'selected':'' }}>Select payment type</option>
                                @foreach($payment_types as $p)
                                <option value="{{ $p->payment_id }}" {{ $p->payment_id===$item->items_payment_type->payment_type?'selected':'' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" class="form-control number" name="quantity" value="{{ $item->quantity }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control currency" name="price" value="{{ $item->price }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Discounted Price <span class="c-gray font-size10px">Optional</span></label>
                            <input type="text" class="form-control currency" name="discounted_price" value="{{ $item->discounted }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto">
                    <div class="margin-top20px">
                         <div class="display-table">
                             <div class="display-row">
                                 <div class="display-cell padding-right5px">
                                    <input type="checkbox" name="allow_reviews" @if($item->allow_reviews==="yes"){{'checked'}}@endif>
                                 </div>
                                 <div class="display-cell">
                                    Allow reviews
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </fieldset>
        </form>
        <div class="button-holder">
            <div class="display-row">
                <div class="display-cell padding-right5px">
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
                <div class="display-cell">
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
                </div>
            </div>
        </div>
        <div class="thehide" id="delete-sale-item-container">
            <form action="{{ url('/app/system/sale-items/item/delete') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                <div class="text-align-center font-700 c-red">Are you sure you want to delete this item? Click 'Delete' to delete this item.</div>
                <div class="button-holder">
                     <button class="btn btn-danger">
                        <div class="display-table">
                            <div class="display-row">
                                <div class="display-cell padding-right5px">
                                    <i class="fa fa-trash"></i>
                                </div>
                                <div class="display-cell">
                                    Delete Item
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
        <div class="margin-top10px">
             <div class="display-table">
                 <div class="display-row">
                     <div class="display-cell padding-right5px">
                         <h5>Reviews:</h5>
                     </div>
                     <div class="display-cell" id="reviews-count">
                         {{ count($item->item_review) }} Reviews
                     </div>
                 </div>
             </div>
        </div>
        <table class="full-width table table-responsive table-bordered dtable font-size13px table-vertical-align-middle table-th-td-align-left" id="item-review-table" data-sort-column="1" data-sort-type="desc">
            <thead>
                <tr>
                    <th>TITLE</th>
                    <th class="thehide">SORTING</th>
                    <th>REVIEW</th>
                    <th>RATING</th>
                    <th>APPROVE/DISAPPROVE<br><span class="font-size10px c-gray">Only when review is approved the review will be visible on the item page review </span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->item_review as $r)
                <tr data-id="{{ $r->id }}">
                    <td>{{ $r->review_title }}</td>
                    <td class="thehide">@if($r->status==="approved"){{'a'}}@else{{'b'}}@endif</td>
                    <td>
                         <div class="display-table center">
                            <button class="btn btn-info item-review-view-review" data-toggle="tooltip" title="Click to view the review" style="font-size:10px;padding:5px 8px;" data-review="{{ $r->review_contents }}">
                                View Review
                            </button>
                        </div>
                    </td>
                    <td>
                        <div class="display-table center">
                        <?php
                            $d = $r->rating;
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
                    </td>
                    <td>
                        @if($r->status==='approved')
                        <div class="display-table center c-megamitch font-700">
                            <div class="display-row">
                                <div class="display-cell padding-right5px"><i class="fa fa-check"></i></div>
                                <div class="display-cell">Approved</div>
                            </div>
                        </div>
                        @else
                        <div class="display-table center">
                            <div class="display-row">
                                <div class="display-cell padding-right5px">
                                   <form action="{{ url('/app/system/sale-items/item/item-review/approve') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="item_review_approve" data-custom-message="none" data-spinner="off">
                                        <input type="hidden" name="id" value="{{ $r->id}}">
                                        <button class="btn btn-success" data-toggle="tooltip" title="Approve this review" style="font-size:10px;padding:5px 8px;">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </button>
                                   </form>
                                </div>
                                <div class="display-cell">
                                    <form action="{{ url('/app/system/sale-items/item/item-review/disapprove') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="item_review_disapprove" data-custom-message="none" data-spinner="off">
                                        <input type="hidden" name="id" value="{{ $r->id}}">
                                        <button class="btn btn-danger" data-toggle="tooltip" title="Disapprove this review" style="font-size:10px;padding:5px 8px;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </form>
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
    <section class="col-lg-3">
        <div class="overflow_auto">
            <div class="display-table">
                 <div class="display-row">
                     <div class="display-cell padding-right5px">
                         <h5>Tags:</h5>
                     </div>
                     <div class="display-cell" id="tags-count">
                         <span id="tag-count">{{ count($item->item_tags) }}</span> Tags
                     </div>
                 </div>
            </div>
             <div id="tag-input" class="form-control" style="min-height:34px;height:auto;overflow-auto;">
                @foreach($item->item_tags as $i)
                <a href="#" class="tag" data-tag-id="{{ $i->tag_id }}" data-toggle="tooltip" title="Click to remove tag">{{ $i->tags->tag_name }}</a>
                @endforeach
                <input type="text" placeholder="Add tag" style="width:35px;">
                <span class="thehide font-size9px" style="width:35px;"></span>
            </div>
            <div id="tag-suggestion" class="overflow-y"></div>
             {{-- <form action="{{ url('/app/system/sale-items/item/create-tag') }}" class="ejex-form thehide" method="post" data-type="json" data-onsuccess="item_add_tags" data-custom-message="none" id="tag-form">
                <input type="text" name="item_id" value="{{ $item->item_id }}">
                <input type="text" name="tag_name">
                <input type="text" name="tag_id">
             </form> --}}
        </div>
        <div class="overflow_auto margin-top10px">
            <div class="display-table">
                 <div class="display-row">
                     <div class="display-cell padding-right5px">
                         <h5>Item Pictures:</h5>
                     </div>
                     <div class="display-cell" id="tags-count">
                         <span id="pictures-count">{{ count($item->item_images) }}</span> Pictures
                     </div>
                 </div>
            </div>
            <div class="overflow-auto radius-3px font-size9px overflow-y padding-15px margin-bottom7px" id="item-pictures-container" style="border:1px solid #ededed;border-bottom-width:2px;min-height:200px;max-height:400px;">
                @if(count($item->item_images)===0)
                 <span>Click the file browser to browse or you can drop item pictures here...</span>
                @else

                @if($default_item_image!==0&&$default_item_image)
                <a href="#" class="primary-item-pic" data-image-id="{{ $default_item_image->id }}" data-image-name="{{ $default_item_image->image_name }}"><figure><img src="{{ url('/app/system/sale-items/item/image/'.$default_item_image->username.'/'.$default_item_image->item_id.'/'.$default_item_image->image_name) }}"></figure></a>
                @endif

                @foreach($item->item_images as $img)
                <a href="#" data-image-id="{{ $img->id }}" data-image-name="{{ $img->image_name }}"><figure><img src="{{ url('/app/system/sale-items/item/image/'.$img->username.'/'.$img->item_id.'/'.$img->image_name) }}"></figure></a>
                @endforeach
                @endif
             </div>
             <input type="file" class="font-size13px" id="browse-pics" multiple>
        </div>
        <div class="overflow_auto margin-top10px">
             <h5>Categories</h5>
        </div>
        <div class="overflow-auto" id="categories-holder">
            @foreach($item->item_categories as $cat)
            <a href="#" class="cat radius-15px bg-gray active-cat" data-toggle="tooltip" title="{{ $cat->cat_desc }}" data-cat-id="{{ $cat->cat_id }}">{{ $cat->category->cat_name }}</a>
            @endforeach
            @foreach($categories as $cat)
            <a href="#" class="cat radius-15px" data-toggle="tooltip" title="{{ $cat->cat_desc }}" data-cat-id="{{ $cat->cat_id }}">{{ $cat->cat_name }}</a>
            @endforeach
        </div>
    </section>
</div>
<!-- /.row -->

@stop


@section('bottom resources')
<script src="{{ url('/core/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ url('/core/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ url('/core/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ url('/core/js/pages/sale_items/sale_items_item_view.js') }}"></script>
@stop