@extends('site.master')

@section('body')

<section class="container">
	<!-- Page Heading -->
	<div class="row">
	    <div class="col-lg-12">
	        <ol class="breadcrumb bg-white">
	            <li @if(!isset($breadcrumbs))class="active"@endif>
	                <i class="fa fa-home"></i> Home
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
</section>
<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				@include('site._left_sidebar')
			</div>
			
			<div class="col-sm-9 padding-right">
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">{{ $query_title }}</h2>
						@foreach($items as $i)
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<a href="{{ url('/product/'.$i->link_name) }}">
											<img src="{{ url($i->default_item_image === 0 ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$i->username.'/'.$i->item_id.'/'.$i->primary_pic) }}" alt="" />
											<h1 class="item-title">
												{{ $i->item_name }}
											</h1>

											@if($i->discounted)
											<h2 class="item-price"><span>{{ $i->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $i->price }}</span></h2>
											@else
											<h2 class="item-price">{{ $i->price }}</h2>
											@endif
											<div class="display-table center">
					                        <?php
					                            $d = $i->rating;
					                            for($x=0;$d>$x;$x++){ ?>
					                            <div class="star-rating rated">
					                                 <span></span>
					                            </div>
					                        <?php } ?>
					                        <?php
					                            $d = 5-$d;
					                            for($x=0;$d>$x;$x++){ ?>
					                            <div class="star-rating unrated">
					                                 <span></span>
					                            </div>
					                        <?php } ?>
					                        </div>
										</a>
										@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
										<div class="choose">
											<ul class="nav nav-pills nav-justified">
												<li>
													@if($i->wishlist==='not login')
														<a href="{{ url('/user/login') }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
													@elseif($i->wishlist)
														<a href="#" class="c-megamitch"><i class="fa fa-check"></i> Already in wishlist</a>
													@else
														<a href="#" class="add-to-wishlist" data-id="{{ $i->item_id }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
													@endif
												</li>
												<li>
													<a href="#" class="add-to-cart" data-id="{{ $i->item_id }}"><i class="fa fa-shopping-cart"></i> Add to cart</a>
												</li>
											</ul>
										</div>
										@endif
									</div>
								</div>
							</div>
						</div>
						@endforeach
				</div><!--new items-->
			</div>
		</div>
	</div>
</section>
@stop