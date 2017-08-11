@extends('site.master')

@section('body')
<section id="slider"><!--slider-->
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="slider-carousel" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
						<li data-target="#slider-carousel" data-slide-to="1"></li>
						<li data-target="#slider-carousel" data-slide-to="2"></li>
					</ol>
					
					<div class="carousel-inner">
						<div class="item active">
							<div class="col-sm-6">
								<h1><img src="{{ url('/core/media/images/logo.png') }}" class="extend"></h1>
								<h2>Your online store and services</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
							</div>
							<div class="col-sm-6">
								<img src="{{ url('/core/site/media/images/home/girl1.jpg') }}" class="girl img-responsive" alt="" />
								<img src="{{ url('/core/site/media/images/home/pricing.png') }}"  class="pricing" alt="" />
							</div>
						</div>
						<div class="item">
							<div class="col-sm-6">
								<h1><img src="{{ url('/core/media/images/logo.png') }}" class="extend"></h1>
								<h2>Your online store and services</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
								<button type="button" class="btn btn-default get">Get it now</button>
							</div>
							<div class="col-sm-6">
								<img src="{{ url('/core/site/media/images/home/girl2.jpg') }}" class="girl img-responsive" alt="" />
								<img src="{{ url('/core/site/media/images/home/pricing.png') }}"  class="pricing" alt="" />
							</div>
						</div>
						
						<div class="item">
							<div class="col-sm-6">
								<h1><img src="{{ url('/core/media/images/logo.png') }}" class="extend"></h1>
								<h2>Your online store and services</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
								<button type="button" class="btn btn-default get">Get it now</button>
							</div>
							<div class="col-sm-6">
								<img src="{{ url('/core/site/media/images/home/girl3.jpg') }}" class="girl img-responsive" alt="" />
								<img src="{{ url('/core/site/media/images/home/pricing.png') }}" class="pricing" alt="" />
							</div>
						</div>
						
					</div>
					
					<a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
						<i class="fa fa-angle-left"></i>
					</a>
					<a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
						<i class="fa fa-angle-right"></i>
					</a>
				</div>
				
			</div>
		</div>
	</div>
</section><!--/slider-->

<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				@include('site._left_sidebar')
			</div>
			
			<div class="col-sm-9 padding-right">
				@if($new_items && count($new_items)>0)
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">New Items</h2>
					@foreach($new_items as $n)
					<div class="col-sm-4">
						<div class="product-image-wrapper">
							<a href="{{ url('/product/'.$n->link_name) }}" class="single-products margin-bottom10px">
								<div class="productinfo text-center">
									<img src="{{ url($n->primary_pic==='no img' ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$n->username.'/'.$n->item_id.'/'.$n->primary_pic) }}" alt="" />
									<h1 class="item-title">
										{{ $n->item_name }}
									</h1>
									@if($n->discounted)
										<h2 class="item-price"><span>{{ $n->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $n->price }}</span></h2>
									@else
										<h2 class="item-price">{{ $n->price }}</h2>
									@endif
									<div class="display-table center">
			                        <?php
			                            $d = $n->rating;
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
								<img src="{{ url('/core/site/media/images/home/new.png') }}" class="new" alt="new" />
							</a>
							@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
							<div class="choose">
								<ul class="nav nav-pills nav-justified">
									<li>
										@if($n->wishlist==='not login')
											<a href="{{ url('/user/login') }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
										@elseif($n->wishlist)
											<a href="#" class="c-megamitch"><i class="fa fa-check"></i> Already in wishlist</a>
										@else
											<a href="#" class="add-to-wishlist" data-id="{{ $n->item_id }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
										@endif
									</li>
									<li>
										<a href="#" class="add-to-cart" data-id="{{ $n->item_id }}"><i class="fa fa-shopping-cart"></i> Add to cart</a>
									</li>
								</ul>
							</div>
							@endif
						</div>
					</div>
					@endforeach
					{{--<div class="col-sm-4">
						<div class="product-image-wrapper">
							<div class="single-products margin-bottom10px">
								<div class="productinfo text-center">
									<img src="{{ url('/core/site/media/images/home/product4.jpg') }}" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								<div class="product-overlay">
									<div class="overlay-content">
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
								</div>
								<img src="{{ url('/core/site/media/images/home/new.png') }}" class="new" alt="" />
							</div>
							<div class="choose">
								<ul class="nav nav-pills nav-justified">
									<li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
									<li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="product-image-wrapper">
							<div class="single-products margin-bottom10px">
								<div class="productinfo text-center">
									<img src="{{ url('/core/site/media/images/home/product5.jpg') }}" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								<div class="product-overlay">
									<div class="overlay-content">
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
								</div>
								<img src="{{ url('/core/site/media/images/home/sale.png') }}" class="new" alt="" />
							</div>
							<div class="choose">
								<ul class="nav nav-pills nav-justified">
									<li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
									<li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
								</ul>
							</div>
						</div>
					</div> --}}
					
					
				</div><!--new items-->
				@endif
				@if(count($categories)!==0)
				<div class="category-tab"><!--category-tab-->
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							@foreach($categories as $c)
							<li @if($loop->first)class="active"@endif ><a href="#{{ $c->link_name }}" data-toggle="tab">{{ $c->cat_name }}</a></li>
							@endforeach
						</ul>
					</div>
					<div class="tab-content">
						@foreach($categories as $c)
						<div class="tab-pane fade @if($loop->first){{ 'active in' }}@endif" id="{{ $c->link_name }}">
							@foreach($c->item as $i)
							@if($i->item->status==="approved"&&$i->item->visibility==="sale")
							<div class="col-sm-4">
								<div class="product-image-wrapper">
									<a href="{{ url('/product/'.$i->item->link_name) }}" class="single-products margin-bottom10px">
										<div class="productinfo text-center">
											<img src="{{ url($i->item->default_item_image === 0 ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$i->item->username.'/'.$i->item->item_id.'/'.$i->primary_pic) }}" alt="" />
											<h1 class="item-title">
												{{ $i->item->item_name }}
											</h1>
											@if($i->item->discounted)
												<h2 class="item-price"><span>{{ $i->item->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $i->item->price }}</span></h2>
											@else
											
											<h2 class="item-price">{{ $i->item->price }}</h2>
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
										</div>
									</a>
									@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
									<div class="choose">
										<ul class="nav nav-pills nav-justified categories-bottom-menu">
											<li>
												@if($i->wishlist==='not login')
													<a href="{{ url('/user/login') }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
												@elseif($i->wishlist)
													<a href="#" class="c-megamitch"><i class="fa fa-check"></i> Already in wishlist</a>
												@else
													<a href="#" class="add-to-wishlist" data-id="{{ $i->item->item_id }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
												@endif
											</li>
											<li>
												<a href="#" class="add-to-cart" data-id="{{ $i->item->item_id }}"><i class="fa fa-shopping-cart"></i> Add to cart</a>
											</li>
										</ul>
									</div>
									@endif
								</div>
							</div>
							@endif
							@endforeach
						</div>
						@endforeach
					</div>
				</div><!--/category-tab-->
				@endif
				@if(count($hot_items)!==0)
				<div class="recommended_items"><!--hot items-->
					<h2 class="title text-center">Hot Items</h2>
					<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner" id="hot-items-carousel">
							@foreach($hot_items as $h)
								<div class="col-sm-4">
									<div class="product-image-wrapper">
										<a href="{{ url('/product/'.$h->link_name) }}" class="single-products margin-bottom10px">
											<div class="productinfo text-center">
												<img src="{{ url($h->default_item_image === 0 ? '/core/media/images/no_img.jpg' : '/sale-items/item/image/'.$h->username.'/'.$h->item_id.'/'.$h->primary_pic) }}" />
												<h1 class="item-title">
													{{ $h->item_name }}
												</h1>
												@if($h->discounted)
													<h2 class="item-price"><span>{{ $h->discounted }}</span>  <span style="text-decoration:line-through;font-weight:300;font-size:12px;">{{ $h->price }}</span></h2>
												@else
													<h2 class="item-price">{{ $h->price }}</h2>
												@endif
												<div class="display-table center">
						                        <?php
						                            $d = $h->rating;
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
											<img src="{{ url('/core/site/media/images/home/hot.png') }}" class="hot" alt="hot" style="position:absolute;top:0px;right:0px;"/>
										</a>
										@if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
										<div class="choose">
											<ul class="nav nav-pills nav-justified">
												<li>
													@if($h->wishlist==='not login')
														<a href="{{ url('/user/login') }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
													@elseif($h->wishlist)
														<a href="#" class="c-megamitch"><i class="fa fa-check"></i> Already in wishlist</a>
													@else
														<a href="#" class="add-to-wishlist" data-id="{{ $h->item_id }}"><i class="fa fa-plus-square"></i> Add to wishlist</a>
													@endif
												</li>
												<li>
													<a href="#" class="add-to-cart" data-id="{{ $h->item_id }}"><i class="fa fa-shopping-cart"></i> Add to cart</a>
												</li>
											</ul>
										</div>
										@endif
									</div>
								</div>
							@endforeach
						</div>
						 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
							<i class="fa fa-angle-left"></i>
						  </a>
						  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
							<i class="fa fa-angle-right"></i>
						  </a>			
					</div>
				</div><!--/recommended_items-->
				@endif
			</div>
		</div>
	</div>
</section>
@stop

@section('bottom resources')

@stop