@extends('layouts.theme')

@section('content')
    @php $pagetype="report"; @endphp

    <h3 class="page-title">Workers | <small style="color: green">All Personnel</small></h3>
    <div class="row">



            <div class="panel">
                <div class="panel-heading">
                    <a href="{{url('/add-new')}}" class="btn btn-primary pull-right" style="margin-bottom: 10px;">Add New</a>
                </div>
                <div class="panel-body">
                    <table class="table  responsive-table" id="products">
                        <thead>
                            <tr style="color: ">
                                <th width="20">#</th>
                                <th>Full Name</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)

                                <tr
                                    @if ($member->gender=="Female")
                                        style="background-color: azure !important;"
                                    @endif
                                >
                                    <td>{{$member->id}}</td>
                                    <td>{{$member->name}}</td>
                                    <td>{{$member->status}}</td>
                                    <td>{{$member->category}}</td>
                                    <td>{{$member->phone_number}}</td>
                                    <td>{{$member->location}}</td>
                                    <td width="90">
                                        <div class="btn-group">
                                            <a href="{{url('/edit-member/'.$member->id)}}" class="label label-primary left"><i class="lnr lnr-pencil"></i></a>
                                            <a href="{{url('/member/'.$member->id)}}/" class="label label-success"><i class="lnr lnr-eye"></i></a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>

    </div>



@endsection
