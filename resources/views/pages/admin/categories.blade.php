@extends('master')

@section('body')

@include('_breadcrumbs')
<div class="row">
    <section class="col-lg-6">
       <div class="display-table">
             <div class="display-row">
                 <div class="display-cell padding-right5px">
                     <h5>Categories:</h5>
                 </div>
                 <div class="display-cell" id="tags-count">
                     <span id="cat-count">{{ count($categories) }}</span> Categories
                 </div>
             </div>
        </div>
       <div class="overflow-auto" id="categories-container">
            @foreach($categories as $cat)
            <a href="#" class="cat radius-15px" style="font-size:15px;padding:4px 8px;" data-tag-id="{{ $cat->cat_id }}" data-toggle="tooltip" title="{{ $cat->cat_desc }}">{{ $cat->cat_name }}</a>
            @endforeach
       </div>
   </section>
   <section class="col-lg-6">
        <h4>Create Category</h4>
       <form action="{{ url('/app/system/admin/categories/create-category') }}" class="ejex-form margin-top20px" method="post" data-type="json" data-onsuccess="create_category" data-custom-message="You have created a category successfully" id="create-category-form">
           <fieldset>
               <div class="form-group">
                   <input type="text" class="form-control" name="cat_name" placeholder="Category name" required>
               </div>
               <div class="form-group">
                   <textarea name="cat_desc" cols="10" rows="5" class="form-control" placeholder="Category description"></textarea>
               </div>
           </fieldset>
           <div class="button-holder">
               <button class="btn btn-success">
                   <i class="fa-fa-check"></i> Create Category
               </button>
           </div>
       </form>
    </section>
</div>
<!-- /.row -->

@stop


@section('bottom resources')
<script src="{{ url('/core/js/pages/admin/categories.js') }}"></script>
@stop