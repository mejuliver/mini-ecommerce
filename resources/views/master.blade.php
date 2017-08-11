<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Buy and Sell">
    <meta name="author" content="Juliver Galleto">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name').' | '.$current_page }}</title>

    <link href="{{ url('/core/plugins/bootstrap-3.3.7/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('/core/plugins/webfonts/font-awesome-4.6.3/css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/core/plugins/webfonts/ionicons-2.0.1/css/ionicons.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('/core/plugins/webfonts/roboto/roboto.css') }}" />

    <link href="{{ url('/core/plugins/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ url('/core/plugins/j_components/j_components.css') }}" rel="stylesheet">
    
    <link href="{{ url('/core/css/sb-admin.css') }}" rel="stylesheet">

    <link href="{{ url('/core/css/main.css') }}" rel="stylesheet">
    @yield('top resources')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    
    <link rel="shortcut icon" type="image/png" href="{{ url('/core/media/images/favicon.png') }}" >
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('/core/media/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ url('/core/media/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ url('/core/media/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ url('/core/media/images/favicon.png') }}">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="j-components thehide">

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('/core/media/images/logo-white.png') }}" class="extend">
                </a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown" id="notification-dp">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <div id="notification-badge">@if(count($notifications)!==0&&$notifications)<span class="badge">{{ count($notifications) }}</span>@endif </div></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li class="padding-zero margin-zero">
                            <ul id="notifications-holder" class="padding-zero margin-zero">
                                @if(count($notifications)!==0&&$notifications)
                                @foreach($notifications as $n)
                                <li>{!! $n->contents !!}</li>
                                @endforeach
                                @else
                                <li class="empty-notification">You have no notifications at the moment</li>    
                                @endif
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ $user_info->first_name.' '.$user_info->last_name }} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ url('/app/system/user/profile') }}"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        {{--<li>
                            <a href="{{ url('/app/system/user/messages') }}"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>--}}
                        <li>
                            <a href="#" id="user-settings"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ url('/logout') }}"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @if(count(array_intersect($roles,['admin','seller']))>0)
            @include('_sidebar')
            @else
            @include('buyer._sidebar')
            @endif
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            @yield('top contents')
            <div class="container-fluid" style="height:100vh;">
                @yield('body')
            </div>
            <!-- /.container-fluid -->
            @yield('bottom contents')
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <button class="btn thehide" data-toggle="modal" data-target="#notification-dialog" id="notification-trigger-button">Open dialog</button>
    <div id="notification-dialog" class="modal fade future-modal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" id="text">
            <div class="modal-header">
                <div class="display-table full-width">
                    <div class="display-row">
                        <div class="display-cell padding-right5px">
                            <h4 class="modal-title display-table full-width text-transform-uppercase">Dialog</h4>
                        </div>
                        <div class="display-cell" style="width:20px;">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body"></div>
          </div>
        </div>
    </div>
    <button class="btn thehide" data-toggle="modal" data-target="#extra-modal" id="extra-modal-trigger-button">Open dialog</button>
    <div id="extra-modal" class="modal fade future-modal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" id="text">
            <div class="modal-header">
                <div class="display-table full-width">
                    <div class="display-row">
                        <div class="display-cell padding-right5px">
                            <h4 class="modal-title display-table full-width text-transform-uppercase">Dialog</h4>
                        </div>
                        <div class="display-cell" style="width:20px;">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body"></div>
          </div>
        </div>
    </div>
    <div class="thehide" id="user-settings-container">
        <form action="{{ url('/app/system/user-settings/save') }}" class="ejex-form" method="post" data-type="json" data-onsuccess="user_settings" data-custom-message="Successfully saved!">
            <fieldset>
                
            </fieldset>
            <div class="button-holder"><button class="btn btn-success">SAVE</button></div>
        </form>
    </div>

    <script>var site_link="{{url('/')}}",username = "@if(Auth::guard('dashboard')->check()){{ Auth::guard('dashboard')->user()->username }}@elseif(Auth::guard('user')->check()){{ Auth::guard('user')->user()->username }}@else{{ 'Not logged' }}@endif";</script>
    <script src="{{ url('/core/plugins/jquery.2.2.3/jquery.min.js') }}"></script>
    <script src="{{ url('/core/plugins/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/core/plugins/momentjs/momentjs.min.js') }}"></script>
    <script src="{{ url('/core/plugins/j_components/j_components.js') }}"></script>
    <!-- socket io -->
    <script src="{{ url('/core/plugins/socketio/socketio.js') }}"></script>
    <script src="{{ url('/core/js/notifications.js') }}"></script>
    @yield('bottom resources')
    <script src="{{ url('/core/js/main.js') }}"></script>
</body>

</html>
