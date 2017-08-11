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
<section id="product-container">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				@include('site._left_sidebar')
			</div>
			<div class="col-sm-9 padding-right">
			@if($breadcrumbs[1]['name']==='All')
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">{{ $query_title }}</h2>
					@foreach($items as $i)
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<a href="{{ url('/product/'.$i->link_name) }}">
											<img src="{{ url($i->default_item_image === 0 ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$i->username.'/'.$i->item_id.'/'.$i->primary_pic) }}" alt="Product Image"/>
											<h1 class="item-title">
												{{ $i->item_name }}
											</h1>
											@if($i->discounted)
											<h2 class="item-price"><span>{{ $i->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $i->price }}</span>
											</h2>
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
										@if(isset($settings_ecommerce)&&$settings_ecommerce===1)
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
				</div>
			@else
				<div class="product-details"><!--product-details-->
					<div class="col-sm-5">
						<div class="view-product">
							<img src="{{ url($product->default_item_image === 0 ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$product->username.'/'.$product->item_id.'/'.$product_primary_image) }}" alt="Product Image" width="329" height="380"/>
						</div>
						@if($product_images->count()!==0)
						<div id="similar-product" class="carousel slide" data-ride="carousel">
							  <!-- Wrapper for slides -->
							    <div class="carousel-inner">
							    	<a href="#"><img src="{{ url('/sale-items/item/image/'.$product->username.'/'.$product->item_id.'/'.$product_primary_image) }}"></a>
									@foreach($product_images as $p)
									  <a href="#"><img src="{{ url('/sale-items/item/image/'.$product->username.'/'.$product->item_id.'/'.$p->image_name) }}"></a>
									@endforeach
								</div>

							  <!-- Controls -->
							  <a class="left item-control" href="#similar-product" data-slide="prev">
								<i class="fa fa-angle-left"></i>
							  </a>
							  <a class="right item-control" href="#similar-product" data-slide="next">
								<i class="fa fa-angle-right"></i>
							  </a>
						</div>
						@endif
					</div>
					<div class="col-sm-7">
						<div class="product-information"><!--/product-information-->
							<h2 class="margin-zero padding-bottom10px">{{ $product->item_name }}</h2>
							<div class="display-table">
		                        <?php
		                            $d = $rating;
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
							<div class="display-table full-width">
								<div class="item-price">
									@if($product->discounted)
										<span>{{ $product->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $product->price }}</span>
									@else
										<span>{{ $product->price }}<span>
									@endif
								</div>
								@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
								@if($product->quantity!==0)
								<label class="quantity-label">Quantity:</label>
								<input type="text" class="quantity-input"/>
								<button type="button" class="btn btn-fefault cart">
									<i class="fa fa-shopping-cart"></i>
									Add to cart
								</button>
								@endif
								@endif
							</div>
							<p><b>Availability:</b> {!! $product->quantity!==0 ? 'In Stock' : '<span class="c-red">Out of stock</span>' !!}</p>
							@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
							<p><b>Payment Type:</b> {{ $product->items_payment_type->payment_type->name }}</p>
							@endif
							<p><b>Description:</b><br>
								{!! $product->item_desc !!}
							</p>
							<a href=""><img src="{{ url('/core/site/media/images/product-details/share.png') }}" class="share img-responsive"  alt="" /></a>
						</div><!--/product-information-->
					</div>
				</div><!--/product-details-->
				
				<div class="category-tab shop-details-tab"><!--category-tab-->
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
							<li><a href="#sellersinfo" data-toggle="tab">Seller's info</a></li>
							<li><a href="#tag" data-toggle="tab">Tags</a></li>
							<li><a href="#reviews" data-toggle="tab">Reviews <span id="review-counter">{{ '('.$product->item_review->count().')' }}</span></a></li>
						</ul>
					</div>
					<div class="tab-content padding-15px">
						<div class="tab-pane fade active in" id="details" >
							{!! $product->details !!}
						</div>
						
						<div class="tab-pane fade" id="sellersinfo" >
							<div class="col-md-2">
								<img src="@if($product->profile->temp_img){{ url('/core/media/images/no_img.jpg') }}@else{{ url('/app/system/user/'.$product->profile->username.'/profile/'.$product->profile->img) }}@endif" alt="{{ $product->profile->first_name.' '.$product->profile->last_name }}" style="width:100px;height:100px;">
							</div>
							<div class="col-md-10">
								<div class="display-table">
									<div class="display-row">
										<div class="display-cell padding-right10px padding-bottom7px">
											<label class="padding-zero margin-zero text-transform-uppercase">Full Name:</label>
										</div>
										<div class="display-cell padding-bottom7px">
											{{ $seller_profile->first_name.' '.$seller_profile->last_name }}
										</div>
									</div>
									<div class="display-row">
										<div class="display-cell padding-right10px padding-bottom7px">
											<label class="padding-zero margin-zero text-transform-uppercase">Email:</label>
										</div>
										<div class="display-cell padding-bottom7px">
											@if(Auth::guard('user')->check())
											{{ $seller_profile->email }}
											@else
											You need to login to view seller's email. <a href="{{ url('/user/login') }}">Click here to login.</a>
											@endif
										</div>
									</div>
									<div class="display-row">
										<div class="display-cell padding-right10px padding-bottom7px">
											<label class="padding-zero margin-zero text-transform-uppercase">Phone:</label>
										</div>
										<div class="display-cell padding-bottom7px">
											@if(Auth::guard('user')->check())
											{{ $seller_profile->phone }}
											@else
											You need to login to view seller's phone. <a href="{{ url('/user/login') }}">Click here to login.</a>
											@endif
										</div>
									</div>
								</div>
								<div style="padding:50px 0px;" class="extend clear display-table">
									<div class="display-table align-left">
										<div class="hexagon" id="reviews-counter" data-toggle="tooltip" title="Seller's Rating">
							                <div class="text-align-center font-700 c-white">
							                    <div class="display-table center">
							                    <?php
							                        $d = $seller_rating;
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
							                    <span>Reviews</span>
							                 </div>
						            	</div>
									</div>
									<div class="display-table align-left">
										<div class="hexagon bg-megamitch" id="verified-seller-badge" data-toggle="tooltip" title="Verified Seller">
							                <div class="text-align-center font-700 c-white">
							                    <div class="display-table center">
							                        <span class="fa fa-shield" style="font-size:50px;"></span><span class="fa fa-check-circle" style="font-size:30px;margin-left:3px;"></span>
							                    </div>
							                 </div>
						            	</div>
									</div>
								</div>
								<a href="{{ url('/product/seller/'.$product->username) }}" class="btn btn-default">See seller's items</a>
							</div>
						</div>
						
						<div class="tab-pane fade" id="tag" >
							<div class="col-md-12">
								@foreach($product->item_tags as $tag)
								<a href="{{ url('/tag'.'/'.$tag->link_name) }}" class="tag">{{ $tag->tags->tag_name }}</a>
								@endforeach
							</div>
						</div>
						
						<div class="tab-pane fade" id="reviews">
							<div class="col-sm-12">
								@if(Auth::guard('user')->check())
								@if($user_has_review==="no")
								<div id="review-notification"></div>
								<form action="{{ url('/rating-system/add-review') }}" class="overflow-auto ejex-form" id="review-form" method="post" data-type="json" data-onsuccess="review" data-custom-message="You have successfully created your review for this item." data-success-function="hide_pokemon" data-message-place="#review-notification">
									<p><b>Write Your Review</b></p>
									<fieldset>
										<input type="hidden" name="id" value="{{ $product->item_id }}">
										<div class="display-table" id="rating-box">
											<div class="display-row">
												<div class="display-cell padding-right3px">
													<a href="#" class="rating">
														<div class="star-rating unrated" style="font-size:30px;"><span></span></div>
													</a>
												</div>
												<div class="display-cell padding-right3px">
													<a href="#" class="rating">
														<div class="star-rating unrated" style="font-size:30px;"><span></span></div>
													</a>
												</div>
												<div class="display-cell padding-right3px">
													<a href="#" class="rating">
														<div class="star-rating unrated" style="font-size:30px;"><span></span></div>
													</a>
												</div>
												<div class="display-cell padding-right3px">
													<a href="#" class="rating">
														<div class="star-rating unrated" style="font-size:30px;"><span></span></div>
													</a>
												</div>
												<div class="display-cell padding-right3px">
													<a href="#" class="rating">
														<div class="star-rating unrated" style="font-size:30px;"><span></span></div>
													</a>
												</div>
											</div>
										</div>
										<input type="hidden" name="rating" value="0">
										<span class="padding-left0 padding-right0 full-width margin-zero">
											<input type="text" name="review_title" class="full-width margin-zero" placeholder="Review title">
										</span>
										<textarea name="review_contents" placeholder="Review contents"></textarea>
									</fieldset>
									<button class="btn btn-default button-holder">
										Submit
									</button>
								</form>
								<div class="j-line margin-top15px margin-bottom7px"></div>
								@endif
								@else
								You must login to create review. <a href="{{ url('/user/login') }}">Click here to login.</a>
								@endif
								<div class="overflow-auto">
									<p><b>Reviews</b></p>
									<div class="margin-top7px" id="product-review-container">
										@if($product->item_review->count()!==0)
											@foreach($product->item_review as $ir)
											<div class="media">
											  <div class="media-left media-top">
											    <a href="#">
											      <img class="media-object" src="{{ url($ir->profile->img===false?'/core/media/images/no_img.jpg':'/app/system/user/'.$ir->username.'/profile/'.$ir->profile->img) }}" alt="{{ $ir->profile->first_name.' '.$ir->profile->last_name }}" style="width:64px;height:64px;">
											    </a>
											  </div>
											  <div class="media-body">
											    <h4 class="media-heading">
											    	{{ $ir->review_title}}
											    	<span class="pull-right">
											    		<?php
									                        $d = $ir->rating;
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
											    	</span>
											    </h4>
											    <p class="padding-zero margin-zero">
											    	{{ $ir->review_contents }}
											    </p>
											    <span class="c-gray font-size13px">{{ $ir->profile->first_name.' '.$ir->profile->last_name }}</span>
											  </div>
											</div>
											@endforeach
										@else
										<span class="no-reviews">No reviews.</span
										@endif
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div><!--/category-tab-->
				
				{{-- <div class="recommended_items"><!--recommended_items-->
					<h2 class="title text-center">recommended items</h2>
					
					<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
							<div class="item active">	
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="/core/site/media/images/home/recommend1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="/core/site/media/images/home/recommend2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="/core/site/media/images/home/recommend3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item">	
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="/core/site/media/images/home/recommend1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="/core/site/media/images/home/recommend2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="/core/site/media/images/home/recommend3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
							<i class="fa fa-angle-left"></i>
						  </a>
						  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
							<i class="fa fa-angle-right"></i>
						  </a>			
					</div>
				</div><!--/recommended_items--> --}}
				
			</div>
			@endif
					
		</div>
	</div>
</section>
@stop

@section('bottom resources')
@if($breadcrumbs[1]['name']!=='All')
<script src="{{ url('/core/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ url('/core/site/plugins/zoom-master/jquery.zoom.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$(".quantity-input").inputmask("numeric", {
		  min: 0,
		  max: parseInt({{ $product->quantity }}),
		  rightAlign: false
		});
		$('#rating-box a').click(function(e){
			e.preventDefault();
			if($(this).find('.star-rating').hasClass('rated')){
				$(this).closest(".display-cell").nextAll().find(".star-rating").removeClass("rated").addClass("unrated");
			}else{
				$(this).find('.star-rating').removeClass("unrated").addClass("rated");
				$(this).closest(".display-cell").prevAll().find(".star-rating").removeClass("unrated").addClass("rated");
			}

			$('input[name="rating"]').val($('#rating-box a > div.rated').length);
		});

		$('.carousel-inner a').click(function(e){
		   e.preventDefault(); 
		   	$(".view-product img").attr("src",$(this).find("img").attr("src")); 
		});

		$('.view-product').zoom();

	});
	
	function review(e){
		if(e.success){
			$('#review-form').remove();
            var d = e.ir.rating,rating='',custom = '<div class="media padding-15px" style="opacity:0.5">',img = e.ir.profile.img===false||!e.ir.profile.img||e.ir.profile.img==='null'||e.ir.profile.img===null?site_link+'/core/media/images/no_img.jpg':site_link+'/app/system/user/'+e.ir.username+'/profile/'+e.ir.profile.img+'" alt="'+e.ir.profile.first_name+' '+e.ir.profile.last_name;
            
            for(var i=0;d>i;i++){
            	rating+='<div class="star-rating rated"><span></span></div>';
        	}
        
            d = 5-d;
            for(var i=0;d>i;i++){
            	rating+='<div class="star-rating unrated"><span></span></div>';
        	}
			
			custom+='<div class="media-left media-top">'+
			    '<a href="#">'+
			      '<img class="media-object" src="'+img+'" style="width:64px;height:64px;">'+
			   '</a>'+
			  '</div>'+
			  '<div class="media-body">'+
			    '<h4 class="media-heading">'+e.ir.review_title+
			    	'<span class="pull-right">'+rating+'</span>'+
			    '</h4>'+
			    '<p class="padding-zero margin-zero">'+e.ir.review_contents+'</p>'+
			    '<span class="c-gray font-size13px">'+e.ir.profile.first_name+' '+e.ir.profile.last_name+'</span>'+
			  '</div>'+
			'</div>';
			$('#product-review-container .no-reviews').remove();
			$('#product-review-container').append(custom);

		}
	}
</script>
@endif
@stop



