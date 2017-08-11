<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Buy and Sell">
    <meta name="author" content="Juliver Galleto">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name').' | Register' }}</title>

    <link href="{{ url('/core/plugins/bootstrap-3.3.7/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('/core/plugins/j_components/j_components.css') }}" rel="stylesheet">
    <link href="{{ url('/css/app.css') }}" rel="stylesheet">
    <link href="{{ url('/core/plugins/webfonts/font-awesome-4.6.3/css/font-awesome.min.css') }}" rel="stylesheet">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
   <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default margin-top20px">
                    <div class="panel-heading font-700">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal ejex-form" role="form" method="POST" action="{{ url('/dashboard/register') }}" data-type="json" data-onsuccess="register" data-custom-message="Successfully registered, redirecting">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">First Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="first_name" autofocus required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Last Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username" class="col-md-4 control-label">Username</label>
                                <div class="col-md-6">
                                    <input type="username" class="form-control" name="username" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username" class="col-md-4 control-label">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>var site_link="{{url('/')}}";</script>
    <script src="{{ url('/core/plugins/jquery.2.2.3/jquery.min.js') }}"></script>
    <script src="{{ url('/core/plugins/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/core/plugins/momentjs/momentjs.min.js') }}"></script>
    <script src="{{ url('/core/plugins/j_components/j_components.js') }}"></script>
    <script>
        var site_link = "{{ url('/') }}";
        function register(e){
            if(e.success){
                location.replace(site_link+'/app/system/dashboard');
            }
        }
    </script>
</body>

</html>
