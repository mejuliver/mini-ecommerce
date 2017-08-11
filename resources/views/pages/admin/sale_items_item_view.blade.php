@extends('master')

@section('top resources')
<link rel="stylesheet" href="{{ url('/core/plugins/datatables/datatables.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ url('/core/css/pages/sale_items_item_view.css') }}" type="text/css">
@stop

@section('body')

@include('_breadcrumbs')
<div class="row item-view" data-item-id="{{ $item->item_id }}">
    <section class="col-lg-9">
        <div class="col-md-12">
            <div class="form-group overflow-auto">
                <label>Item Name</label>
                <div class="j-text">{{ $item->item_name }}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group overflow-auto">
                <label>Item Descriptions</label>
                <div class="j-text">{!! $item->item_desc !!}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group overflow-auto">
                <label>Item Details</label>
                <div class="j-text">{!! $item->details !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Item Status</label>
                <div class="j-text">{{ $item->status }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Payment Type</label>
                <div class="j-text">{{ $item->items_payment_type->payment_type->name }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Quantity</label>
                <div class="j-text">{{ $item->quantity }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Price</label>
                <div class="j-text">{{ $item->price }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Discounted Price</label>
                <div class="j-text">{{ $item->discounted }}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group overflow-auto">
                <div class="margin-top20px">
                     <div class="display-table">
                         <div class="display-row">
                             <div class="display-cell padding-right5px">
                                <label>Allow Reviews:</label>
                             </div>
                             <div class="display-cell">
                                @if($item->allow_reviews==="yes")
                                    <i class="fa fa-check c-megamitch"></i>
                                @else
                                    <i class="fa fa-times c-red"></i>
                                @endif
                             </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
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
            <table class="full-width table table-responsive table-bordered dtable font-size13px table-vertical-align-middle table-th-td-align-left" id="item-review-table" data-sort-column="1" data-sort-type="asc">
                <thead>
                    <tr>
                        <th>TITLE</th>
                        <th class="thehide">SORTING</th>
                        <th>REVIEW</th>
                        <th>RATING</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->item_review as $r)
                    <tr data-id="{{ $r->id }}">
                        <td>{{ $r->review_title }}</td>
                        <td class="thehide">{{ $r->created_at }}</td>
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
                            <div class="display-table center">
                                <div class="display-row">
                            @if($r->status === 'approved')
                                <div class="display-cell padding-right5px c-megamitch">
                                     <i class="fa fa-check"></i>
                                 </div>
                                 <div class="display-cell c-megamitch">
                                     Approved
                                 </div> 
                            @else
                                <div class="display-cell padding-right5px c-yellow">
                                     <i class="fa fa-clock-o"></i>
                                 </div>
                                 <div class="display-cell c-yellow">
                                     Pending
                                 </div>
                            @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <section class="col-lg-3">
        <div class="overflow_auto margin-top17px">
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
             <div style="min-height:34px;height:auto;overflow-auto;">
                @foreach($item->item_tags as $i)
                <a href="#" class="tag" data-tag-id="{{ $i->tag_id }}" data-toggle="tooltip" title="Click to remove tag">{{ $i->tags->tag_name }}</a>
                @endforeach
            </div>
            <div id="tag-suggestion" class="overflow-y"></div>
        </div>
        <div class="overflow_auto margin-top17px">
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
            <div id="item-pictures-container" class="overflow-auto radius-3px font-size9px overflow-y padding-15px" style="border:1px solid #ededed;border-bottom-width:2px;min-height:200px;max-height:400px;">

                @if($default_item_image!==0&&$default_item_image)
                <a href="#" class="primary-item-pic" data-image-id="{{ $default_item_image->id }}" data-image-name="{{ $default_item_image->image_name }}"><figure><img src="{{ url('/app/system/sale-items/item/image/'.$default_item_image->username.'/'.$default_item_image->item_id.'/'.$default_item_image->image_name) }}"></figure></a>
                @endif

                @foreach($item->item_images as $img)
                <a href="#" data-image-id="{{ $img->id }}" data-image-name="{{ $img->image_name }}"><figure><img src="{{ url('/app/system/sale-items/item/image/'.$img->username.'/'.$img->item_id.'/'.$img->image_name) }}"></figure></a>
                @endforeach
                
             </div>
        </div>
        <div class="overflow_auto margin-top17px">
             <h5>Categories</h5>
        </div>
        <div class="overflow-auto">
            @foreach($item->item_categories as $cat)
            <a href="#" class="cat radius-15px bg-gray active-cat" data-toggle="tooltip" title="{{ $cat->cat_desc }}">{{ $cat->category->cat_name }}</a>
            @endforeach
            @foreach($categories as $cat)
            <a href="#" class="cat radius-15px" data-toggle="tooltip" title="{{ $cat->cat_desc }}">{{ $cat->cat_name }}</a>
            @endforeach
        </div>
    </section>
</div>
<!-- /.row -->
@stop


@section('bottom resources')
<script src="{{ url('/core/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ url('/core/js/pages/admin/sale_items_item_view.js') }}"></script>
@stop