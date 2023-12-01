@extends('layouts.theme')

@section('content')
    @php $pagename="dashboard"; @endphp

    <h3 class="page-title">Dashboard | <small style="color: green">Periodic Performance</small></h3>
    <div class="row">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Stats</h3>
            </div>
            <div class="panel-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="card-box bg-blue">
                                <div class="inner">
                                    <h3>{{$jobs->where('account_head',1)->count()}}</h3>
                                    <p>Sales</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-portfolio" aria-hidden="true"></i>
                                </div>
                                <a href="{{url('transactions')}}" class="card-box-footer">View All<i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card-box bg-orange">
                                <div class="inner">
                                    <h3> {{$jobs->where('account_head','!=',1)->count()}} </h3>
                                    <p>Proforma Invoices</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                </div>
                                <a href="{{url('proformas')}}" class="card-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card-box bg-green">
                                <div class="inner">
                                    <h3> {{$customers->count()}} </h3>
                                    <p> Total Customers</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                </div>
                                <a href="/customers" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>



                        <div class="col-lg-3 col-sm-6">
                            <div class="card-box bg-red">
                                <div class="inner">
                                    <h3> {{$mytasks->count()}} </h3>
                                    <p> Tasks / To Do </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-tasks"></i>
                                </div>
                                <a href="{{url('/tasks')}}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Sales | this year</h3>
                </div>
                <div class="panel-body">

                    <div id="sales-chart" style="height: 300px"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Reminders</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>

                            <tr>

                                <th>Title</th>
                                <th>Date</th>
                                <th>Activities</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mytasks as $ta)
                                @if ($ta->category=="Reminder")

                                    <tr>
                                        <td>{{$ta->title}}</td>
                                        <td>{{$ta->from==$ta->to?$ta->from:$ta->from." to ".$ta->to}}</td>
                                        <td>{{$ta->activities}}</td>

                                    </tr>
                                @endif
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        body{
            background:#eee;
            }

            .card-box {
                position: relative;
                color: #fff;
                padding: 20px 10px 40px;
                margin: 20px 0px;
            }
            .card-box:hover {
                text-decoration: none;
                color: #f1f1f1;
            }
            .card-box:hover .icon i {
                font-size: 100px;
                transition: 1s;
                -webkit-transition: 1s;
            }
            .card-box .inner {
                padding: 5px 10px 0 10px;
            }
            .card-box h3 {
                font-size: 27px;
                font-weight: bold;
                margin: 0 0 8px 0;
                white-space: nowrap;
                padding: 0;
                text-align: left;
            }
            .card-box p {
                font-size: 15px;
            }
            .card-box .icon {
                position: absolute;
                top: auto;
                bottom: 5px;
                right: 5px;
                z-index: 0;
                font-size: 72px;
                color: rgba(0, 0, 0, 0.15);
            }
            .card-box .card-box-footer {
                position: absolute;
                left: 0px;
                bottom: 0px;
                text-align: center;
                padding: 3px 0;
                color: rgba(255, 255, 255, 0.8);
                background: rgba(0, 0, 0, 0.1);
                width: 100%;
                text-decoration: none;
            }
            .card-box:hover .card-box-footer {
                background: rgba(0, 0, 0, 0.3);
            }
            .bg-blue {
                background-color: #00c0ef !important;
            }
            .bg-green {
                background-color: #00a65a !important;
            }
            .bg-orange {
                background-color: #f39c12 !important;
            }
            .bg-red {
                background-color: #d9534f !important;
            }

    </style>


@endsection
