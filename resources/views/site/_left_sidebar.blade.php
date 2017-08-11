<div class="left-sidebar">
    <h2>Categories</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
        @foreach($categories as $cat)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="{{ url('/category/'.$cat->link_name) }}">{{ $cat->cat_name }} <span class="pull-right">({{ $cat->items_count }})</span></a></h4>
            </div>
        </div>
        @endforeach
    </div><!--/category-products-->
</div>