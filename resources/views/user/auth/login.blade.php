<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name').' | Login' }}</title>
    <link href="{{ url('/core/plugins/bootstrap-3.3.7/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/core/plugins/webfonts/ionicons-2.0.1/css/ionicons.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('/core/plugins/webfonts/roboto/roboto.css') }}" />
    <link href="{{ url('/core/plugins/webfonts/font-awesome-4.6.3/css/font-awesome.min.css') }}" rel="stylesheet">

    <link href="{{ url('/core/plugins/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ url('/core/plugins/j_components/j_components.css') }}" rel="stylesheet">
    <link href="{{ url('/core/site/plugins/prettyPhoto/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ url('/core/site/plugins/price-range/price-range.css') }}" rel="stylesheet">
    <link href="{{ url('/core/site/css/main.css') }}" rel="stylesheet">
    <link href="{{ url('/core/site/css/responsive.css') }}" rel="stylesheet">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    @yield('top resources')
    <!--[if lt IE 9]>
    <script src="/core/site/plugins/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" type="image/png" href="{{ url('/core/media/images/favicon.png') }}" >
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('/core/media/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ url('/core/media/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ url('/core/media/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ url('/core/media/images/favicon.png') }}">
</head><!--/head-->

<body class="thehide">
    <header id="header"><!--header-->
        <div class="header_top"><!--header_top-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                @foreach($settings as $s)
                                @if($s->settings->settings_name==='telephone number')
                                <li><a href="#"><i class="fa fa-phone"></i> {{ $s->settings_value}}</a></li>
                                @elseif($s->settings->settings_name==='email')
                                <li><a href="#"><i class="fa fa-envelope"></i> {{ $s->settings_value}}</a></li>
                                @endif
                                @endforeach
                                <li><a href="{{ url('/dashboard/register') }}">Sell With Us</a></li>
                                @if(Auth::guard('dashboard')->check())
                                <li><a href="{{ url('/logout') }}"> Logout</a></li>
                                @else
                                <li><a href="{{ url('/dashboard/login') }}"> Login</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header_top-->
        
        <div class="header-middle"><!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="{{ url('/') }}" class="c-dark" id="logo">
                                 <img src="{{ url('/core/media/images/logo.png') }}" class="extend">
                                 <p>Your Online Store and Services</p>
                            </a>
                        </div>
                        {{--
                        <div class="btn-group pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                    USA
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Canada</a></li>
                                    <li><a href="#">UK</a></li>
                                </ul>
                            </div>
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                    DOLLAR
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Canadian Dollar</a></li>
                                    <li><a href="#">Pound</a></li>
                                </ul>
                            </div>
                        </div>
                        --}}
                    </div>
                    <div class="col-sm-8">
                        <div class="shop-menu pull-right" style>
                            <ul class="nav navbar-nav">
                                @if(Auth::guard('user')->check())
                                @if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
                                <li><a href="{{ url('/app/system/user/dashboard') }}"><i class="fa fa-user"></i> Account</a></li>
                                @endif
                                @elseif(Auth::guard('dashboard')->check())
                                <li><a href="{{ url('/app/system/dashboard') }}"><i class="fa fa-user"></i> Account</a></li>
                                @endif
                                @if(isset($settings_ecommerce)&&$settings_ecommerce==='1')
                                <li><a href="{{ url('/app/system/wishlists') }}"><i class="fa fa-star"></i> Wishlist</a></li>
                                <li><a href="{{ url('/check-out') }}"><i class="fa fa-crosshairs"></i> Checkout</a></li>
                                <li><a href="{{ url('/cart') }}"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                                @endif
                                @if(!Auth::guard('user')->check())
                                <li><a href="{{ url('/user/login') }}"><i class="fa fa-lock"></i> Login</a></li>
                                @else
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-lock"></i> Logout</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header-middle-->
    
        <div class="header-bottom"><!--header-bottom-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>

                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">
                                <li><a href="{{ url('/home') }}" class="active">Home</a></li>
                                <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="{{ url('/products/all') }}">Products</a></li>
                                        <li><a href="{{ url('/categories') }}">Categories</a></li>
                                    </ul>
                                </li> 
                                <li><a href="{{ url('/') }}/blog">Blog</a></li> 
                                <li><a href="{{ url('/contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="search_box">
                            <form action="{{ url('/search') }}" method="get">
                                <input type="text" placeholder="Search" id="search" class="full-width" name="search"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header-bottom-->
    </header><!--/header-->
    <section id="form"><!--form-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form"><!--login form-->
                        <h2>Login to your account</h2>
                        
                        <form action="{{ url('/user/login') }}" method="post">
                            {{ csrf_field() }}
                            @if ($errors->has('username') || $errors->has('password'))
                            <div class="font_size13px alert alert-danger" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px">
                                    <tbody>
                                        <tr>
                                            <td class="padding_right10px" style="width:25px;" valign="top"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                            <td class="font_size13px text_align_left">
                                                @if($errors->has('username'))
                                                {{ $errors->first('username') }}
                                                @elseif($errors->has('password'))
                                                {{ $errors->first('password') }}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <input type="text" placeholder="Username" name="username" required/>
                            <input type="password" placeholder="Password" name="password" required/>
                            <span>
                                <input type="checkbox" class="checkbox" name="remember"> 
                                Keep me signed in
                            </span>
                            <button type="submit" class="btn btn-default">Login</button>
                        </form>
                    </div><!--/login form-->
                </div>
                <div class="col-sm-1">
                    <h2 class="or">OR</h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form"><!--sign up form-->
                        <h2>New User Signup!</h2>
                        <form action="{{ url('/user/register') }}" method="post" class="ejex-form" data-type="json" data-onsuccess="register" data-custom-message="Successfully registered, redirecting to your dashboard...">
                            {{ csrf_field() }}
                            <input type="text" placeholder="First name" name="first_name" required/>
                            <input type="text" placeholder="Last name" name="last_name" required/>
                            <input type="email" placeholder="Email address" name="email" required/>
                            <input type="text" placeholder="Username" name="username" required/>
                            <input type="password" placeholder="Password" name="password" required/>
                            <input type="password" placeholder="Confirm password" name="password_confirmation" required/>
                            <button type="submit" class="btn btn-default">Signup</button>
                        </form>
                    </div><!--/sign up form-->
                </div>
            </div>
        </div>
    </section><!--/form-->
    <footer id="footer"><!--Footer-->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="companyinfo">
                            <h2><img src="{{ url('/core/media/images/logo.png') }}" alt=""></h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,sed do eiusmod tempor</p>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ url('/core/site/media/images/home/iframe1.png') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ url('/core/site/media/images/home/iframe2.png') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ url('/core/site/media/images/home/iframe3.png') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="{{ url('/core/site/media/images/home/iframe4.png') }}" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="address">
                            <img src="{{ url('/core/site/media/images/home/map.png') }}" alt="" />
                            <p>Iligan City, Lone District, Philippines</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-widget">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Service</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Online Help</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Order Status</a></li>
                                <li><a href="#">Change Location</a></li>
                                <li><a href="#">FAQ’s</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Quock Shop</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">T-Shirt</a></li>
                                <li><a href="#">Mens</a></li>
                                <li><a href="#">Womens</a></li>
                                <li><a href="#">Gift Cards</a></li>
                                <li><a href="#">Shoes</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Policies</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Terms of Use</a></li>
                                <li><a href="#">Privecy Policy</a></li>
                                <li><a href="#">Refund Policy</a></li>
                                <li><a href="#">Billing System</a></li>
                                <li><a href="#">Ticket System</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>About Shopper</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Company Information</a></li>
                                <li><a href="#">Careers</a></li>
                                <li><a href="#">Store Location</a></li>
                                <li><a href="#">Affillate Program</a></li>
                                <li><a href="#">Copyright</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 col-sm-offset-1">
                        <div class="single-widget">
                            <h2>About DINHI</h2>
                            <form action="#" class="searchform">
                                <input type="text" placeholder="Your email address" />
                                <button type="submit" class="btn btn-default"><i class="fa fa-arrow-circle-o-right"></i></button>
                                <p>Get the most recent updates from <br />our site and be updated your self...</p>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <p class="text-align-center">Copyright © 2016 DINHI. All rights reserved.</p>
                    
                </div>
            </div>
        </div>
        
    </footer><!--/Footer-->
    <script>var site_link="{{url('/')}}";</script>
    <script src="{{ url('/core/plugins/jquery.2.2.3/jquery.min.js') }}"></script>
    <script src="{{ url('/core/plugins/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/core/plugins/momentjs/momentjs.min.js') }}"></script>
    <script src="{{ url('/core/site/plugins/scrollUp/scrollUp.min.js') }}"></script>
    <script src="{{ url('/core/plugins/j_components/j_components.js') }}"></script>
    <script>
        function register(e){
            if(e.success){
                location.replace(site_link+'/app/system/user/dashboard');
            }
        }
    </script>
</body>
</html>