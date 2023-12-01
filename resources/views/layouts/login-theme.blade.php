<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>{{$settings->business_name}} | Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/linearicons/style.css')}}">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/main.css')}}">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="{{ asset('assets/css/demo.css')}}">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/favicon.png')}}">
	<link rel="stylesheet" href="{{ asset('assets/jquery-ui/jquery-ui.min.css') }}">
	<style>
		.auth-box{
			overflow-y: visible !important;
			height: auto !important;
			min-height: 100%p;
		}

		.left, .right{

			overflow-y: visible !important;
			height: auto !important;
			min-height: 500px;
		}

		.auth-box .right .overlay{
			opacity: 0.92;
			background-color: {{$settings->color}} !important;

		}

		 .logocontainer{
			display: flex !important;
			justify-content: center !important;
			align-items: center !important;

		}

		.logo{
			height: 100px;
			width: 100px;
			border: white 4px solid;
			box-shadow: 2px 2px #ccc;
			margin-bottom: -20px;
			z-index: 99999999999999999999999;
            background-color: white;
		}
​


	</style>
</head>

<body>



	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">

			<div class="vertical-align-middle">

				<div class="logocontainer">
					<img  src="{{asset('public/images/'.$settings->logo)}}" alt="{{$settings->motto}}" class="logo  img-circle">
				</div>

				<div class="auth-box ">
					<div class="left">
						<div class="content">
                                <!-----------------------------START YIELD PAGE CONTENT -->
                                @yield('content')
                                <!----------------------------END YIELD PAGE CONTENT -->
                            </div>
                        </div>
                        <div class="right" style="background-image: url('public/images/{{$settings->background}}'); background-repeat: no-repeat; background-size: cover;">
                            <div class="overlay"></div>
                            <div class="content text">

                                <h1 style="margin-top: 100px;">{{$settings->ministry_name}}</h1>
                                <p>{{$settings->motto}}</p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END WRAPPER -->
    </body>

    </html>
	<script src="{{asset('/assets/vendor/jquery/jquery.min.js')}}"></script>
	<script src="{{asset('/assets/jquery-ui/jquery-ui.min.js')}}"></script>


	<script type="text/javascript">

        $('#newbusiness_box').hide();

        $('#setting_id').change(function(){
            if($(this).val() == 'None'){
                $('#newbusiness_box').toggle();
            }else{
                $('#newbusiness_box').hide();

            }
        });


        $(function() {
            $('.datepicker').datepicker({
				yearRange: "-80:+0",
                changeYear: true,

            });
		});

	</script>


