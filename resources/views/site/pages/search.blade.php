@extends('site.master')

@section('body')
<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				@include('site._left_sidebar')
			</div>
			<div class="col-sm-9 padding-right">
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">SEARCH RESULTS FOR {{ $search }}</h2>
					@foreach($search_results as $sr)
					<div class="col-sm-4">
						<div class="product-image-wrapper">
							<a href="{{ url('/product/'.$sr->link_name) }}" class="single-products margin-bottom10px">
								<div class="productinfo text-center">
									<img src="{{ url($sr->primary_pic==='no img' ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$sr->username.'/'.$sr->item_id.'/'.$sr->primary_pic) }}" alt="" />
									<h1 class="item-title">
										{{ $sr->item_name }}
									</h1>
									@if($sr->discounted)
										<h2 class="item-price"><span>{{ $sr->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $sr->price }}</span></h2>
									@else
										<h2 class="item-price">{{ $sr->price }}</h2>
									@endif
									<div class="display-table center">
			                        <?php
			                            $d = $sr->rating;
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
								</div>
							</a>
							@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
							<div class="choose">
								<ul class="nav nav-pills nav-justified">
									<li>
										@if($sr->wishlist==='not login')
											<a href="{{ url('/user/login') }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
										@elseif($sr->wishlist)
											<a href="#" class="c-megamitch"><i class="fa fa-check"></i> Already in wishlist</a>
										@else
											<a href="#" class="add-to-wishlist" data-id="{{ $sr->item_id }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
										@endif
									</li>
									<li>
										<a href="#" class="add-to-cart" data-id="{{ $sr->item_id }}"><i class="fa fa-shopping-cart"></i> Add to cart</a>
									</li>
								</ul>
							</div>
							@endif
						</div>
					</div>
					@endforeach
				</div><!--new items-->
			</div>
		</div>
	</div>
</section>
@stop

@section('bottom resources')

@stop