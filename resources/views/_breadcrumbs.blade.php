<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li @if(!isset($breadcrumbs))class="active"@endif>
                <i class="fa fa-dashboard"></i> Dashboard
            </li>
            @if(isset($breadcrumbs))
			@foreach($breadcrumbs as $b)
			<li><a href="{{$b['link']}}">{{$b['name']}}</a></li>
			@endforeach
            @endif
        </ol>
    </div>
</div>
<!-- /.row -->