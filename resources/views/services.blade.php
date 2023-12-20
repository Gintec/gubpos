@extends('layouts.theme')

@section('content')
    @php $modal="material"; $pagename = "Sales Report"; $pagetype="report"; @endphp

    <h3 class="page-title">Services | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">
                        <div style="text-align: center;">
                            <a href="{{url('/new_service')}}" class="btn btn-success"><i class="lnr lnr-cart"></i> Add New Service</a>
                        </div>
                </div>
                <div class="panel-body">
                    <table id="servicesTable" class="table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Start Date</th>
                                <th>Completion Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td>{{ $service->user->name }}</td>
                                    <td>{{ $service->start_date }}</td>
                                    <td>{{ $service->completion_date }}</td>
                                    <td>{{ $service->category }}</td>
                                    <td>{{ $service->description }}</td>
                                    <td>{{ $service->status }}</td>
                                    <td>{{ $service->amount }}</td>
                                    <td>
                                        <a href="{{ route('service', $service->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('edit-service', $service->id) }}" class="btn btn-primary btn-sm">Edit</a>

                                        <a href="{{ route('del-service', $service->id) }}" class="btn btn-danger btn-sm">Delete</a>                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>

    </div>

@endsection
