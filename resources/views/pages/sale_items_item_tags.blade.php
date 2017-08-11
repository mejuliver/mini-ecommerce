@extends('master')

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-9">
       <form action="{{ url('/app/system/sale-items/tags/create-tag') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="create_tag" data-custom-message="You have created a tag successfully." id="create-tag-form">
           <div class="form-group">
               <input type="text" class="form-control" name="tag_name" placeholder="Create tag">
           </div>
       </form>
       <div class="overflow-auto margin-top10px">
           <div class="display-table">
                 <div class="display-row">
                     <div class="display-cell padding-right5px">
                         <h5>My Tags:</h5>
                     </div>
                     <div class="display-cell" id="tags-count">
                         <span id="my-tags-count">{{ count($my_tags) }}</span> Tags
                     </div>
                 </div>
            </div>
           <div class="overflow-auto" id="my-tags">
                @foreach($my_tags as $tag)
                <a href="#" class="tag" data-tag-id="{{ $tag->tag_id }}" data-toggle="tooltip" title="Click to delete tag">{{ $tag->tag_name }}</a>
                @endforeach
           </div>
       </div>
    </section>
    <section class="col-lg-3">
        <div class="overflow_auto">
            <div class="display-table">
                 <div class="display-row">
                     <div class="display-cell padding-right5px">
                         <h5>All Tags:</h5>
                     </div>
                     <div class="display-cell" id="tags-count">
                         <span id="all-tags-count">{{ count($my_tags)+count($tags) }}</span> Tags
                     </div>
                 </div>
            </div>
             <div id="all-tags" class="overflow-auto">
                @foreach($my_tags as $i)
                <a href="#" class="tag bg-megamitch" data-tag-id="{{ $i->tag_id }}">{{ $i->tag_name }}</a>
                @endforeach
                @foreach($tags as $i)
                <a href="#" class="tag bg-gray" data-tag-id="{{ $i->tag_id }}">{{ $i->tag_name }}</a>
                @endforeach
            </div>
        </div>
    </section>
</div>
<!-- /.row -->

@stop


@section('bottom resources')
<script src="{{ url('/core/js/pages/sale_items/sale_items_item_tags.js') }}"></script>
@stop