<!doctype html>
<html lang="en">

<head>
	<title>{{$settings->business_name}}  | Dashboard</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/linearicons/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/chartist/css/chartist-custom.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/jquery-ui/jquery-ui.min.css') }}">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
	<link rel="icon" type="{{ asset('image/png" sizes="96x96" href="assets/img/favicon.png') }}">

    <link rel="stylesheet" href="{{asset('/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css')}}">
	<style>
		.brand{
			padding: 10px 5px 5px 10px !important;
			width: 25%;
		}

        p[data-f-id="pbf"] {
            visibility: hidden;
            display: none;
            height: 0px;
            background-color: none !important;
        }

        .grid-container {
            display: grid;
            gap: 10px;
            grid-template-columns: auto auto auto;
            background-color:aliceblue;
            padding: 10px;
        }

        .grid-item {
            background-color:beige;
            border: 1px solid rgba(0, 0, 0, 0.8);
            padding: 10px;
            font-size: 20px;
            text-align: center;
        }

        @media only screen and (max-width: 800px) {
            .navbar-nav{
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

            #navbar-menu{
                width: 80% !important;
            }
        }
	</style>
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="brand row">
				<div class="col-lg-3">
					<img  src="{{asset('public/images/'.$settings->logo) }}" class="img-responsive logo" style="height: auto !important; width: 100%; position: relative; padding: 0px;">
				</div>
				<div class="col-lg-9">
					<b style="color: {{$settings->color}}">{{$settings->business_name}}</b><br>
					<small>{{$settings->motto}}</small>
				</div>
			</div>
			<div class="container-fluid">
				<div class="navbar-btn">
					<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-menu"></i></button>
				</div>
                @if(Auth()->user()->role!="Customer")
                    <form class="navbar-form navbar-left" action="{{ route('searchmembers') }}" method="post">
                        @csrf
                        <div class="input-group">
                            <input type="text" value="" name="keyword" class="form-control" placeholder="Search ...">
                            <span class="input-group-btn"><button type="submit" class="btn btn-primary">Go</button></span>
                        </div>
                    </form>
                @endif
				<!--
                <div class="navbar-btn navbar-btn-right">
					<a class="btn btn-success update-pro" href="{{ url('/add-new')}}" title="New Member" target="_blank"><span class="fa fa-user-plus"></span> <span>New Member</span></a>
				</div>
            -->
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">

								<a href="#" class="dropdown-toggle icon-menu" data-toggle="dropdown">
									<i class="lnr lnr-alarm"></i>
									@if ($mytasks->count()>0)
										<span class="badge bg-danger">{{$mytasks->count()}} <!-- Some Laravel Counter --></span>
									@endif
								</a>


							<ul class="dropdown-menu notifications">
								@foreach ($mytasks as $ts)
									<li><a href="{{ url('/tasks')}}" class="notification-item"><span class="dot bg-warning"></span>{{$ts->title}} | <i class="lnr lnr-clock"></i>{{$ts->date}}</a></li>
								@endforeach
								<li><a href="{{ url('/tasks')}}" class="more">See all notifications</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="lnr lnr-user"></i> <span>@auth {{ Auth::user()->name }} @endauth </span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
							<ul class="dropdown-menu">
                                @if(Auth()->user()->role!="Customer")
                                    <li><a href="{{ url('/member/'.$login_user->id)}}"><i class="lnr lnr-user"></i> <span>My Profile</span></a></li>
                                    <li><a href="{{ url('/tasks')}}"><i class="lnr lnr-envelope"></i> <span>Message</span></a></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#settings"><i class="lnr lnr-cog"></i> <span>Settings</span></a></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#switchbusiness"><i class="lnr lnr-sync"></i> <span>Switch Business</span></a></li>
                                    <li><a href="{{ url('/members')}}"><i class="lnr lnr-user"></i> <span>Users</span></a></li>
                                @endif
                                <li><a href="{{ url('/logout')}}"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
							</ul>
						</li>
                        @if(Auth()->user()->role!="Customer")
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="lnr lnr-sync"></i> <span>Switch Dashboard</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/sales')}}"><i class="lnr lnr-cart"></i> <span>Sales</span></a></li>
                                    <li><a href="{{ url('/services')}}"><i class="lnr lnr-cog"></i> <span>Services</span></a></li>
                                </ul>
                            </li>
                        @endif

					</ul>
				</div>
			</div>
		</nav>
		<!-- END NAVBAR -->
		<!-- LEFT SIDEBAR -->
		<div id="sidebar-nav" class="sidebar" style="margin-top: 10px">
			<div class="sidebar-scroll">
				<nav>
                    @if(Auth()->user()->role!="Customer")
                        <ul class="nav">
                            <li><a href="{{ url('/home')}}" class="active"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>

                            <li class="roledlink Admin Super Staff" style="visibility:hidden;">
                                <a href="#subPages5" data-toggle="collapse" class="collapsed"><i class="lnr lnr-cart"></i> <span>Sales</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages5" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/newsales')}}" class="roledlink Admin Super Staff">New Sales</a></li>
                                        <li><a href="{{ url('/sales')}}" class="roledlink Admin Super Staff">Sales Records</a></li>

                                    </ul>
                                </div>
                            </li>

                            <li class="roledlink Admin Super Staff" style="visibility:hidden;">
                                <a href="#subPages12" data-toggle="collapse" class="collapsed"><i class="lnr lnr-cart"></i> <span>Invoices</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages12" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/invoices')}}" class="roledlink Admin Super Staff">Sales Invoices</a></li>

                                        <li><a href="{{ url('/newproforma')}}" class="roledlink Admin Super Staff">New Proforma Invoice</a></li>
                                        <li><a href="{{ url('/proformas')}}" class="roledlink Admin Super Staff">Proforma Invoices</a></li>

                                    </ul>
                                </div>
                            </li>
                            <li class="roledlink Staff Admin Finance Super" style="visibility:hidden;">
                                <a href="#subPages6" data-toggle="collapse" class="collapsed"><i class="lnr lnr-users"></i> <span>Customers</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages6" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/add-new')}}" class="">Add New</a></li>
                                        <li><a href="{{ url('/customers')}}" class="">Customers</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="roledlink Admin Super Staff" style="visibility:hidden;">
                                <a href="#subPages11" data-toggle="collapse" class="collapsed"><i class="lnr lnr-cog"></i> <span>Services</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages11" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/new_service')}}" class="roledlink Admin Super Staff">New Service</a></li>
                                        <li><a href="{{ url('/services')}}" class="roledlink Admin Super Staff">All Service Records</a></li>

                                    </ul>
                                </div>
                            </li>


                            <li class="roledlink Staff Admin Finance Super" style="visibility:hidden;">
                                <a href="#subPages4" data-toggle="collapse" class="collapsed"><i class="lnr lnr-menu"></i> <span>Products</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages4" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/products')}}" class="">Product List</a></li>
                                        <li><a href="{{ url('/psupplies')}}" class="">Supplied Products</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="roledlink Staff Admin Finance Super" style="visibility:hidden;">
                                <a href="#subPages13" data-toggle="collapse" class="collapsed"><i class="lnr lnr-users"></i> <span>Users</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages13" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/customers')}}" class="">Customers</a></li>
                                        <li><a href="{{ url('/members')}}" class="">Personnel</a></li>
                                        <li><a href="{{ url('/suppliers')}}" class="">Suppliers</a></li>

                                    </ul>
                                </div>
                            </li>

                            <li class="roledlink Admin Super Finance" style="visibility:hidden;">
                                <a href="#subPages9" data-toggle="collapse" class="collapsed"><i class="lnr lnr-list"></i> <span>Finance</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages9" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/transactions')}}" class="">Transactions</a></li>
                                        <li><a href="{{ url('/account-heads')}}" class="">Manage Account Heads</a></li>

                                    </ul>
                                </div>
                            </li>

                            <li class="roledlink Admin Super Staff" style="visibility:hidden;">
                                <a href="#subPages8" data-toggle="collapse" class="collapsed"><i class="lnr lnr-flag"></i> <span>Management</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages8" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/tasks')}}" class="">Manage Tasks/ToDo</a></li>
                                        <li><a href="{{ url('/businesses')}}" class="roledlink Super">Manage Businesses</a></li>
                                        <li><a href="{{ url('/categories')}}">Manage Categories</a></li>
                                        <li><a href="{{ url('/help')}}">Basic Use</a></li>

                                    </ul>
                                </div>
                            </li>

                            <li class="roledlink Admin Super Staff" style="visibility:hidden;">
                                <a href="#subPages10" data-toggle="collapse" class="collapsed"><i class="lnr lnr-envelope"></i> <span>Communication</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages10" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/communications')}}" class="">Send Bulk SMS</a></li>
                                        <li><a href="{{ url('/sentmessages')}}" class="">Sent Messages</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @else
                        <ul class="nav">
                            <li><a href="{{ url('/home')}}" class="active"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>

                            <li class="roledlink Customer Supplier Driver Distributor" style="visibility:hidden;">
                                <a href="#subPages2" data-toggle="collapse" class="collapsed"><i class="lnr lnr-settings"></i> <span>Menu</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                                <div id="subPages2" class="collapse ">
                                    <ul class="nav">
                                        <li><a href="{{ url('/myinvoices')}}" class="">Invoices / Orders</a></li>
                                        <li><a href="{{ url('/mytickets')}}" class="">Tickets</a></li>
                                        <li><a href="{{ url('/logout')}}" class="">Logout</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @endif
				</nav>
			</div>
		</div>
		<!-- END LEFT SIDEBAR -->
		<!-- MAIN -->
		<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid" style="position: relative !important; width: 100%;">
					<!-----------------------------START YIELD PAGE CONTENT -->
					@if (Session::get('message'))
                        @php
                            $type = 'success';
                            if (Session::get('type')=='Error'){
                                $type = 'danger';
                            }
                        @endphp
						<div class="alert alert-{{$type}} alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							<i class="fa fa-check-circle"></i> <b>{!!Session::get('message')!!}</b>
						</div>
					@endif
					@yield('content')

					<!----------------------------END YIELD PAGE CONTENT -->
				</div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
		<footer>
			<div class="container-fluid">
				<p class="copyright">&copy; {{date("Y")}} <a href="https://www.prosales.com.ng" target="_blank">{{$settings->name}}</a>. All Rights Reserved.</p>
			</div>
		</footer>
	</div>
	<!-- END WRAPPER -->
	<!-- Javascript -->
	<script src="{{asset('/assets/vendor/jquery/jquery.min.js')}}"></script>
	<script src="{{asset('/assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
	<script src="{{asset('/assets/scripts/klorofil-common.js')}}"></script>
	<script src="{{asset('/assets/jquery-ui/jquery-ui.min.js')}}"></script>
	<script src="{{asset('/assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>


</body>

</html>

<!-- The Settings Modal -->
<div class="modal" id="settings">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Application Settings</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-check">
                <input type="checkbox" name="create_new_business" id="create_new_business" class="form-check-input">
                <label class="form-check-label"><small style="color: {{$settings->color}}"><i>Click Here to Create New Business</i></small></label>
            </div>

            <form method="POST" action="{{ route('settings') }}" id="settingsform" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{$settings->id}}">

				<input type="hidden" name="oldlogo" value="{{$settings->logo}}">

				<input type="hidden" name="oldbackground" value="{{$settings->background}}">

                <input type="hidden" name="newbusiness" id="newbusiness" value="">

                <div class="form-group">
                    <label for="businessgroup_id"  class="control-label ">Business Group/Head Office</label>
                    <select class="form-control" name="businessgroup_id" id="businessgroup_id">
                        <option value="{{ $settings->businessgroup_id }}" selected>{{ $businessgroups->where('id',$settings->businessgroup_id)->first()->businessgroup_name}}</option>
                        @foreach ($businessgroups as $mg)
                            <option value="{{$mg->id}}">{{$mg->businessgroup_name}}</option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label for="business_name">Business / Facility Name</label>
                    <input type="text" name="business_name" id="business_name" class="form-control" value="{{$settings->business_name}}">
                </div>

				<div class="form-group">
                    <label for="motto">Motto</label>
                    <input type="text" name="motto" id="motto" class="form-control" value="{{$settings->motto}}">
                </div>

				<div class="form-group">
                    <label for="address">Address | Phone Number</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{$settings->address}}">
                </div>




                <div class="form-group">
                    <label for="logo">Upload Logo Image</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                </div>

				<div class="form-group">
                    <label for="background">Upload Background Image</label>
                    <input type="file" name="background" id="background" class="form-control">
                </div>

				<div class="form-group">
                    <label for="color">Choose System Colour</label>
                    <input type="color" name="color" id="color" class="form-control" value="{{$settings->color}}">
                </div>

                <div class="form-group">
                    <label for="user_id"  class="control-label ">Admin User</label>
                    <select class="form-control" name="user_id" id="user_id">
                        <option value="{{ $settings->user_id }}" selected>{{ $settings->user->name}}</option>
                        @foreach ($customers as $hm)
                            <option value="{{$hm->id}}">{{$hm->name}}</option>
                        @endforeach

                    </select>
                </div>

				<div class="form-group">
				  <label for="mode">Mode</label>
				  <select class="form-control" name="mode" id="mode">
					  <option value="{{$settings->mode}}">{{$settings->mode}}</option>
					<option value="Active" selected>Active</option>
					<option value="Maintenance">Maintenance</option>
				  </select>
				</div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save Settings') }}
                    </button>
                </div>


            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
</div>

<div class="modal" id="switchbusiness">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Switch Business/Facility</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <form method="POST" action="{{ route('switchbusiness') }}" enctype="multipart/form-data">
                @csrf

                @if (isset($userbusinesses) && $userbusinesses->count()>0)

                    <div class="form-group row">
                        <div class="col-sm-8">
                            <select class="form-control" name="settings_id" id="settings_id">

                                <option value="{{ $login_user->setting_id }}" selected>{{ $login_user->settings->business_name}}</option>
                                @foreach ($businessgroups as $businessgroup)
                                    @if (Auth()->user()->role=="Super")
                                        @foreach ($businessgroup->settings as $usrmin)
                                            <option value="{{$usrmin->id}}">{{$usrmin->business_name}}</option>
                                        @endforeach
                                    @else
                                        @foreach ($businessgroup->settings->where('user_id',Auth()->user()->id) as $usrmin)
                                            <option value="{{$usrmin->id}}">{{$usrmin->business_name}}</option>
                                        @endforeach
                                    @endif

                                @endforeach

                            </select>

                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Switch') }}
                                </button>
                            </div>
                        </div>

                    </div>
                @else

               No Business to Switch

                @endif
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
</div>

@if (isset($pagename) && $pagename=="staff_dashboard")
	<script src="{{asset('/js/highcharts.js')}}"></script>


	<script type="text/javascript">
        var salesData = <?php echo json_encode($salesData)?>;
        Highcharts.chart('container', {
            title: {
                text: 'Sales Data ',{{date('Y')}}
            },
            subtitle: {
                text: 'Source: Sales Record'
            },
            xAxis: {
                categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ]
            },
            yAxis: {
                title: {
                    text: 'Number of Sales By Product Name'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [{
                name: 'Sales Record',
                data: salesData
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>
@endif

@if (isset($pagename) && $pagename=="production_dashboard")
	<script src="{{asset('/js/highcharts.js')}}"></script>

	<script type="text/javascript">
        var productionData = <?php echo json_encode($productionData)?>;
        Highcharts.chart('container', {
            title: {
                text: 'Production Data ',{{date('Y')}}
            },
            subtitle: {
                text: 'Source: Production Record'
            },
            xAxis: {
                categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ]
            },
            yAxis: {
                title: {
                    text: 'Number of Production By Product Name'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [{
                name: 'Production Record',
                data: productionData
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>
@endif
@if (isset($pagetype) && $pagetype=="report")

	<script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>

    <script src="{{asset('/js/dataTables.fixedHeader.min.js')}}"></script>

    <script src="{{asset('/js/dataTables.select.min.js')}}"></script>

    <script src="{{asset('/js/dataTables.searchPanes.min.js')}}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js" /></script>

	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js" /></script>

	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js" /></script>

	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js" /></script>

	<script>

		// TABLES WITH FILTERS
		$('#products thead tr').clone(true).appendTo( '#products thead' );
		$('#products thead tr:eq(1) th:not(:last)').each( function (i) {
			var title = $(this).text();
			$(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" value="" />' );

			$( 'input', this ).on( 'keyup change', function () {
				if ( table.column(i).search() !== this.value ) {
					table
						.column(i)
						.search( this.value )
						.draw();
				}
			} );
		} );


		var table = $('#products').DataTable( {
			orderCellsTop: true,
			fixedHeader: true,
			"order": [[ 0, "asc" ]],
			"paging": false,
			"pageLength": 50,
			"filter": true,
			"ordering": true,
			deferRender: true,
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		} );
	</script>
@endif
@if (isset($pagename) && $pagename=="programmes")
    <link href="{{asset('node_modules/froala-editor/css/froala_editor.pkgd.min.css')}}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{{asset('node_modules/froala-editor/js/froala_editor.pkgd.min.js')}}"></script>
    <script>
        var editor = new FroalaEditor('.richtext');
    </script>
    <style>
        .fr-wrapper div a{
            visibility: hidden;
            display: none;
            height: 0px;
            background-color: none !important;
        }

    </style>
@endif


	<script>
		function accountHead(accid){



					var title = $('#ach'+accid).attr("data-title");
					var category = $('#ach'+accid).attr("data-category");
					var type = $('#ach'+accid).attr("data-type");
					var description = $('#ach'+accid).attr("data-description");

					$('#id').val(accid);
					$('#title').val(title);
					$('#category').val(category).attr("selected", "selected");
					$('#type').val(type);
					$('#description').val(description);


		}

        function category(accid){
            var title = $('#ach'+accid).attr("data-title");
            var category_group = $('#ach'+accid).attr("data-category_group");
            var description = $('#ach'+accid).attr("data-description");
            var setting_id = $('#ach'+accid).attr("data-setting_id");


            $('#id').val(accid);
            $('#title').val(title);
            $('#category_group').val(category_group).attr("selected", "selected");
            $('#description').val(description);
            $('#catbutton').html("Update Category");
            $('#setting_id').val(setting_id);

        }

        function productionjob(accid){
            var batchno = $('#ach'+accid).attr("data-batchno");

            var target_quantity = $('#ach'+accid).attr("data-target_quantity");
            var product_id = $('#ach'+accid).attr("data-product_id");
            var staff_incharge = $('#ach'+accid).attr("data-staff_incharge");
            var dated_started = $('#ach'+accid).attr("data-dated_started");
            var dated_ended = $('#ach'+accid).attr("data-dated_ended");
            var status = $('#ach'+accid).attr("data-status");
            var estimated_cost_of_production = $('#ach'+accid).attr("data-estimated_cost_of_production");
            var setting_id = $('#ach'+accid).attr("data-setting_id");

            $('#id').val(accid);
            $('#batchno').val(batchno);
            $('#target_quantity').val(target_quantity);
            $('#product_id').val(product_id).attr("selected", "selected");
            $('#staff_incharge').val(staff_incharge).attr("selected", "selected");
            $('#dated_started').val(dated_started);
            $('#dated_ended').val(dated_ended);
            $('#status').val(status);
            $('#estimated_cost_of_production').val(estimated_cost_of_production);
            $('#setting_id').val(setting_id);

            $('#pjobbutton').html("Update Production Job");
        }

		function attendance(accid){



			var date = $('#ach'+accid).attr("data-date");
			var activity = $('#ach'+accid).attr("data-activity");
			var men = $('#ach'+accid).attr("data-men");
			var women = $('#ach'+accid).attr("data-women");
			var children = $('#ach'+accid).attr("data-children");
			var remarks = $('#ach'+accid).attr("data-remarks");

			$('#id').val(accid);
			$('#date').val(date);
			$('#activity').val(activity).attr("selected", "selected");
			$('#men').val(men);
			$('#women').val(women);
			$('#children').val(children);
			$('#remarks').val(remarks);

		}

		function transaction(accid){


			var title = $('#ach'+accid).attr("data-title");
			var date = $('#ach'+accid).attr("data-date");
			var account_head = $('#ach'+accid).attr("data-account_head");
			var amount = $('#ach'+accid).attr("data-amount");
			var reference_no = $('#ach'+accid).attr("data-reference_no");
			var detail = $('#ach'+accid).attr("data-detail");
			var from = $('#ach'+accid).attr("data-from");
			var to = $('#ach'+accid).attr("data-to");
			var approved_by = $('#ach'+accid).attr("data-approved_by");
			var recorded_by = $('#ach'+accid).attr("data-recorded_by");

			$('#title').val(title);
			$('#id').val(accid);
			$('#date').val(date);
			$('#account_head').val(account_head).attr("selected", "selected");
			$('#amount').val(amount);
			$('#reference_no').val(reference_no);
			$('#detail').val(detail);
			$('#from').val(from).attr("selected", "selected");
			$('#to').val(to).attr("selected", "selected");
			$('#approved_by').val(approved_by).attr("selected", "selected");
			$('#recorded_by').val(recorded_by).attr("selected", "selected");

		}

		function business(accid){



			var name = $('#ach'+accid).attr("data-name");
			var details = $('#ach'+accid).attr("data-details");
			var leader = $('#ach'+accid).attr("data-leader");
			var activities = $('#ach'+accid).attr("data-activities");

			$('#id').val(accid);
			$('#name').val(name);
			$('#details').val(details);
			$('#leader').val(leader).attr("selected", "selected");
			$('#activities').text(activities);

		}

        function material(accid){

            var name = $('#ach'+accid).attr("data-name");
            var type = $('#ach'+accid).attr("data-type");
            var measurement_unit = $('#ach'+accid).attr("data-measurement_unit");
            var size = $('#ach'+accid).attr("data-size");
            var picture = $('#ach'+accid).attr("data-picture");

            var cost_per = $('#ach'+accid).attr("data-cost_per");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var category = $('#ach'+accid).attr("data-category");

            $('#id').val(accid);
            $('#name').val(name);
            $('#type').val(type).attr("selected", "selected");
            $('#measurement_unit').val(measurement_unit);
            $('#size').val(size);



            $('#cost_per').val(cost_per);
            $('#oldpicture').val(picture);
            $('#category').val(category).attr("selected", "selected");
            $('#setting_id').val(setting_id).attr("selected", "selected");
            $('#matbutton').html("Update Material");

        }


        function materialdamages(accid){

            var name = $('#ach'+accid).attr("data-name");
            var material_id = $('#ach'+accid).attr("data-material_id");
            var invoiceno = $('#ach'+accid).attr("data-invoiceno");
            var batchno = $('#ach'+accid).attr("data-batchno");
            var reason = $('#ach'+accid).attr("data-reason");

            var quantity = $('#ach'+accid).attr("data-quantity");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var dated = $('#ach'+accid).attr("data-dated");
            var damaged_by = $('#ach'+accid).attr("data-damaged_by");

            $('#id').val(accid);
            $('#name').val(name);
            $('#material_id').val(material_id).attr("selected", "selected");
            $('#invoiceno').val(invoiceno);
            $('#batchno').val(batchno);
            $('#dated').val(dated);


            $('#reason').val(reason);
            $('#quantity').val(quantity);
            $('#damaged_by').val(damaged_by).attr("selected", "selected");
            $('#setting_id').val(setting_id).attr("selected", "selected");
            $('#matdbutton').html("Update Damaged Material");

        }

        function productDamages(accid){

            var product_name = $('#ach'+accid).attr("data-product_name");
            var product_id = $('#ach'+accid).attr("data-product_id");
            var invoiceno = $('#ach'+accid).attr("data-invoiceno");
            var batchno = $('#ach'+accid).attr("data-batchno");
            var reason = $('#ach'+accid).attr("data-reason");

            var quantity = $('#ach'+accid).attr("data-quantity");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var dated = $('#ach'+accid).attr("data-dated");
            var damaged_by = $('#ach'+accid).attr("data-damaged_by");

            $('#id').val(accid);
            $('#name').val(name);
            $('#product_id').val(product_id).attr("selected", "selected");
            $('#invoiceno').val(invoiceno);
            $('#batchno').val(batchno);
            $('#dated').val(dated);


            $('#reason').val(reason);
            $('#quantity').val(quantity);
            $('#damaged_by').val(damaged_by).attr("selected", "selected");
            $('#setting_id').val(setting_id).attr("selected", "selected");
            $('#prddbutton').html("Update Damaged Product");

        }

        function materialcheckout(accid){

            $("#forcheckout").show();

            $("#materiallist").hide();

            var material_name = $('#ach'+accid).attr("data-material_name");

            $("#material_named").text(material_name).change();

            $("#quantity").attr('type','number').change();

            var checkout_by = $('#ach'+accid).attr("data-checkout_by");
            var approved_by = $('#ach'+accid).attr("data-approved_by");
            var production_batch = $('#ach'+accid).attr("data-production_batch");

            var material_id = $('#ach'+accid).attr("data-material_id");
            var quantity = $('#ach'+accid).attr("data-quantity");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var dated = $('#ach'+accid).attr("data-dated");
            var details = $('#ach'+accid).attr("data-details");

            var date_supplied = $('#ach'+accid).attr("data-date_supplied");


            $('#id').val(accid);
            $('#production_batch').val(production_batch).attr("selected", "selected").change();
            $('#checkout_by').val(checkout_by).attr("selected", "selected").change();
            $('#material_id').val(material_id).attr("selected", "selected").change();
            $('#quantity').val(quantity);
            $('#dated').val(dated);
            $('#details').val(details);
            $('#approved_by').val(approved_by).attr("selected", "selected").change();

            $('#setting_id').val(setting_id).attr("selected", "selected").change();
            $('#mtcbutton').html("Update Checkout");

        }

        function product(accid){

            var name = $('#ach'+accid).attr("data-name");
            var type = $('#ach'+accid).attr("data-type");
            var measurement_unit = $('#ach'+accid).attr("data-measurement_unit");
            var size = $('#ach'+accid).attr("data-size");
            var picture = $('#ach'+accid).attr("data-picture");

            var price = $('#ach'+accid).attr("data-price");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var category = $('#ach'+accid).attr("data-category");

            $('#id').val(accid);
            $('#name').val(name);
            $('#type').val(type).attr("selected", "selected");
            $('#measurement_unit').val(measurement_unit);
            $('#size').val(size);



            $('#price').val(price);
            $('#oldpicture').val(picture);
            $('#category').val(category).attr("selected", "selected");
            $('#setting_id').val(setting_id).attr("selected", "selected");
            $('#prdbutton').html("Update Product");

        }

        function supplier(accid){

            var supplier_name = $('#ach'+accid).attr("data-supplier_name");
            var company_name = $('#ach'+accid).attr("data-company_name");
            var phone_number = $('#ach'+accid).attr("data-phone_number");
            var details = $('#ach'+accid).attr("data-details");
            var address = $('#ach'+accid).attr("data-address");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var category = $('#ach'+accid).attr("data-category");

            $('#id').val(accid);
            $('#company_name').val(company_name);
            $('#supplier_name').val(supplier_name);
            $('#phone_number').val(phone_number);
            $('#details').val(details);
            $('#address').val(address);
            $('#category').val(category).attr("selected", "selected").change();
            $('#setting_id').val(setting_id).attr("selected", "selected").change();
            $('#supbutton').html("Update Supplier");

        }

        function supply(accid){

            var supplier_id = $('#ach'+accid).attr("data-supplier_id");
            var material_id = $('#ach'+accid).attr("data-material_id");
            var quantity = $('#ach'+accid).attr("data-quantity");
            var cost_per = $('#ach'+accid).attr("data-cost_per");
            var total_amount = $('#ach'+accid).attr("data-total_amount");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var confirmed_by = $('#ach'+accid).attr("data-confirmed_by");
            var batchno = $('#ach'+accid).attr("data-batchno");

            var date_supplied = $('#ach'+accid).attr("data-date_supplied");

            $('#updating').val("No");

            $('#id').val(accid);
            $('#supplier_id').val(supplier_id).attr("selected", "selected").change();;
            $('#material_id').val(material_id).attr("selected", "selected").change();;
            $('#quantity').val(quantity);
            $('#cost_per').val(cost_per);
            $('#total_amount').val(total_amount);
            $('#date_supplied').val(date_supplied);
            $('#confirmed_by').val(confirmed_by);
            $('#batchno').val(batchno);

            $('#setting_id').val(setting_id).attr("selected", "selected").change();
            $('#spbutton').html("Update Supply");

        }
        function psupply(accid){

            var supplier_id = $('#ach'+accid).attr("data-supplier_id");
            var product_id = $('#ach'+accid).attr("data-product_id");
            var quantity = $('#ach'+accid).attr("data-quantity");
            var cost_per = $('#ach'+accid).attr("data-cost_per");
            var total_amount = $('#ach'+accid).attr("data-total_amount");
            var setting_id = $('#ach'+accid).attr("data-setting_id");
            var confirmed_by = $('#ach'+accid).attr("data-confirmed_by");
            var batchno = $('#ach'+accid).attr("data-batchno");
            var date_supplied = $('#ach'+accid).attr("data-date_supplied");


            $('#id').val(accid);
            $('#supplier_id').val(supplier_id).attr("selected", "selected").change();;
            $('#product_id').val(product_id).attr("selected", "selected").change();;
            $('#quantity').val(quantity);
            $('#cost_per').val(cost_per);
            $('#total_amount').val(total_amount);
            $('#date_supplied').val(date_supplied);
            $('#confirmed_by').val(confirmed_by);
            $('#batchno').val(batchno);

            $('#setting_id').val(setting_id).attr("selected", "selected").change();
            $('#pspbutton').html("Update Product Supply");

        }

		function programme(accid){

            var title = $('#ach'+accid).attr("data-title");
			var type = $('#ach'+accid).attr("data-type");
			var from = $('#ach'+accid).attr("data-from");
			var to = $('#ach'+accid).attr("data-to");
            var picture = $('#ach'+accid).attr("data-pic");

			var details = $('#ach'+accid).attr("data-details");
			var category = $('#ach'+accid).attr("data-category");
			var business = $('#ach'+accid).attr("data-business");

			$('#id').val(accid);
			$('#title').val(title);
			$('#type').val(type).attr("selected", "selected");
			$('#from').val(from);
			$('#to').val(to);



			$('#details').val(details);
            $('#oldpicture').val(picture);
			$('#category').val(category).attr("selected", "selected");
			$('#business').val(business).attr("selected", "selected");

            // var editor2 = new FroalaEditor('.richtext', {}, function () {
            // Call the method inside the initialized event.
            editor.html.set(details);
            // });
		}

		function addnumber(number){
			var receivers = $('#recipients').val();

			if(number=="all"){

				if(receivers==""){
					$('#recipients').val($('#all').attr('data-allnumbers'));
				}else{
					$('#recipients').val('');
				}


			}else{
				if($("#recipients").val().indexOf(','+number) >= 0){



					$('#recipients').val(receivers.replace(','+number,''));

				}else if($("#recipients").val().indexOf(number+',') >= 0){


					$('#recipients').val(receivers.replace(number+',',''));

				}else if($("#recipients").val().indexOf(number) >= 0){


					$('#recipients').val(receivers.replace(number,''));

				}else{
					if(receivers==""){

						$('#recipients').val(number);
					}else{
						$('#recipients').val(receivers+','+number);
					}

				}
			}

		}

        function addItem(item){

            var pid = $('#item'+item).attr("data-pid");
			var unit = $('#item'+item).attr("data-price");
            var in_stock = $('#item'+item).attr("data-in_stock");
            var name = $('#item'+item).attr("data-name");
            var munit = $('#item'+item).attr("data-munit");

            munit = addCommas(munit);
            unit = addCommas(unit);

            $("table tbody#item_list").append("<tr id='itrow"+item+"'><td class='form-group'><input id='item"+item+"' type='hidden' name='product_id[]' class='form-control' value='"+pid+"' readonly><h5 id='itname"+item+"'>"+name+"</h5><small><i>(Stock: "+in_stock+")</i></small></td><td class='form-group'><input id='qty"+item+"'  onchange='changeQty("+item+")' type='number' value='1' name='qty[]' class='form-control quantity'><span><small>"+munit+"</small></span></td><td class='form-group'><input id='unit"+item+"' type='text' onchange='changeUc("+item+")' name='unit[]'  value='"+unit+"' class='form-control numberInput'  pattern='[0-9,]*'></td><td class='form-group'><input id='amount"+item+"' type='text' name='amount[]'  value='"+unit+"' class='form-control amount numberInput'  pattern='[0-9,]*' readonly></td><td class='form-group'><a href='#' class='badge badge-danger removeitem' id='re"+item+"'>X</a></td></tr>");

            // let value = $('#item'+item+).val().replace(/[^\d,]/g, ''); // Remove non-digit and non-comma characters
            // value = addCommas(value);
            // $('.numberInput').val(value);

            reCalc();

            $('#item'+item).hide();
        };

        function changeQty(clicked){
            // RECALCULATE AMOUNT OF ONE ITEM ON QUANTITY CHANGE
            var qty = $("#qty"+clicked).val();
            var unit_rate = $("#unit"+clicked).val().replace(/,/g, '');
            var new_amount = parseFloat(qty)*parseFloat(unit_rate);
            new_amount = addCommas(new_amount);
            $("#amount"+clicked).val(new_amount);
            reCalc();
        }

        $(document).on('input','.form-control',function(event){
            reCalc();
        });

        function changeUc(clicked){
            // RECALCULATE AMOUNT OF ONE ITEM ON QUANTITY CHANGE
            var uc = $("#unit"+clicked).val().replace(/,/g, '');
            var qty = $("#qty"+clicked).val().replace(/,/g, '');
            var new_amount = parseFloat(qty)*parseFloat(uc);
            new_amount = addCommas(new_amount);
            $("#amount"+clicked).val(new_amount);
            reCalc();
        }

        function reCalc(){

            // alert("Am Recalculating...");
            // RECALCULATE TOTAL AMOUNT
            var sum = 0;
            $(".amount").each(function(){
                sum += +$(this).val().replace(/,/g, '');

                // alert($(this).val().replace(/,/g, ''));
            });
            $("#total_due").val(sum.toFixed(2));

            // alert(sum);


            // RECALCULATE TOTAL DISCOUNT
            var total_discount = $("#discount").val().replace(/,/g, '');

            //$(".discount").each(function(){
              //  total_discount += +$(this).val();
            // });

            // $("#total_discount").val(total_discount.toFixed(2));

            var tax_percent = $("#tax_percent").val().replace(/,/g, '');
            tax = parseFloat(tax_percent)*(parseFloat(sum)/100);

            $("#tax").val(tax);

            // var total_discount = $("#total_discount").val();

            var new_sum = (parseFloat(sum)+parseFloat(tax)) - parseFloat(total_discount);
            new_sum = addCommas(new_sum);
            $("#total_due").val(new_sum);

            $("#amount_paid").val($("#total_due").val());

        }

		// CHECK ALL
		$('#all').click(function(event) {
			if(this.checked) {
				// Iterate each checkbox
				$(':checkbox').each(function() {
					this.checked = true;
				});
			} else {
				$(':checkbox').each(function() {
					this.checked = false;
				});
			}
		});

		// TEXT AREA Counter
		$('#body').on("input", function(){
			var maxlength = $(this).attr("maxlength");
			var currentLength = $(this).val().length;

			$("#charcounter").text(currentLength + " characters");
			$("#pagecounter").text(Math.ceil(currentLength/160) + " pages");


			if( currentLength >= maxlength ){
				$("#error").text("You have reached the maximum number of characters.");
			}else{
				$("#charleft").text(maxlength - currentLength + " chars left");

			}
		});

		var usrRole = "{{$login_user->role}}";

		$(".roledlink").hide();
		function protect() {
			$("." + usrRole).css("visibility", "visible");
			$("." + usrRole).show();
		}
		protect();

		// $( ".datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});

        $(function() {
            $('.datepicker').datepicker({
				yearRange: "-80:+0",
                changeYear: true,
                dateFormat: 'yy-mm-dd',

            });
		});

        $("#create_new_business").click(function(){
            var token = $("input[name=_token]").val();
            $('#settingsform')[0].reset();
            $(':input').val('');
            // $(':select').val('');
            $('#newbusiness').val('Yes');
            $("input[name=_token]").val(token);
        });

        $(function(){
            $(".fr-wrapper div a").hide();
        });

        $(".modaldismiss,.close").click(function(){
            // $('#programmesform')[0].reset();
            var token = $("input[name=_token]").val();
            $('#newbusiness').val('Yes');
            $(':input').val('');
            $("input[name=_token]").val(token);
            // editor.html.set("");
        });

        // ADD MATERIAL CHECKOUT
        $(".add_item").click(function(){
            // alert("Am here!");
            // $(".spechead").show();
            var item_class = $(".add_item").attr("id");
            var old_class = parseFloat(item_class);
            new_class = old_class+1;
            $(".add_item").prop('id', new_class);

            $("#1").clone().attr('id',new_class).appendTo("#item_list");
            $("#"+new_class +" a").prop('id',"re"+new_class);
            reCalc();

            // $("table tbody#item_list").append("<tr scope='row' class='row"+new_class+"'><td class='input-field'><input type='text' name='property[]' value='' placeholder='e.g. Color, Brand etc'></td><td class='input-field'><td class='input-field'><input type='text' name='value[]' value='' placeholder='e.g. Red, HP etc'></td><td><a href='#' class='btn-floating red btn-small delpos' onClick='delRow("+new_class+")'><i class='small material-icons'>remove</i></a></td></tr>");

        });

        $(document.body).on('click','.removeitem',function(event) {

            var item_id = $(this).attr("id");
            item_id = item_id.substr(2);

            $("#"+item_id).remove();
            $("#itrow"+item_id).remove();
            if($(".amount").length >= 0) {
                reCalc();
                $('#item'+item_id).toggle();
            }

        });

        $("#supplyupdate").click(function(){
            $('#updating').val("Yes");
        });

        $(document.body).on('change','.mid',function(event) {

            var unit = $(this).find(':selected').attr('data-units');
            $(this).parent().parent().children('td.unit').children('span').text(unit);
        });

        $(document).on('change','.jobstatus',function(event) {
            var jstatus = $(this).find(':selected').val();
            if(jstatus=="Completed"){
                $("#forcompleted").show();
            }
        });

        $(document.body).on('change','#customer',function(event) {
            // var elementValue = $(this).val();
            var buyer = $("#customers option[value='" + $('#customer').val() + "']").text()

            $("#buyer").val(buyer);

        });

        $("#select_customer").on('change',function(event) {
            var customer = $(this).find(':selected').val();
            if(customer=="New"){
                $("#customer_form").show();
            }else{
                $("#customer_form").hide();
            }
        });

        $("#dfee").hide();
        $("#add_delivery:checkbox").click(function(event) {

            if ($(this).is(":checked")){
                $("#dfee").show();
            }else{
                $("#dfee").hide();
            }
        });

        $("#forcheckout").hide();
        $("#forcompleted").hide();
        $("#customer_form").hide();

        $('.numberInput').on('input', function(event) {
            let value = $(this).val().replace(/[^\d,]/g, ''); // Remove non-digit and non-comma characters
            value = addCommas(value);
            $(this).val(value);
        });


        function addCommas(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
	</script>



