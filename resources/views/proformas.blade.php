@extends('layouts.theme')

@section('content')
    @php $pagetype="report"; $modal="accounthead"; @endphp

    <h3 class="page-title">Proforma | <small style="color: green">Invoices</small></h3>
    <div class="row">
            <div class="panel" style="width:100%">
                <div class="panel-heading" style="text-align: center">

                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#transaction"> <i class="fa fa-plus"></i> Add New</a>


                </div>
                <div class="panel-body">
                    <table class="table  responsive-table" style="width: 100%; position: relative" id="products">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Ref. No</th>
                                <th>Customer</th>
                                <th>Entered By</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transact)

                                <tr>
                                    <td>{{$transact->title}}</td>
                                    <td>{{number_format($transact->amount,2)}}</td>
                                    <td>{{$transact->dated}}</td>
                                    <td>{{strtoupper($transact->reference_no)}}</td>

                                    <td>{{is_numeric($transact->recorded_by)?$users->where('id',$transact->recorded_by)->first()->name:$transact->recorded_by}}</td>
                                    <td>

                                        <a href="{{url('/edit-invoice/'.$transact->id)}}" target="_blank" class="label label-success">Edit Invoice</a>
                                        @if (auth()->user()->role=="Super")
                                            <a href="{{url('/delete-trans/'.$transact->id)}}" class="label label-danger Super"  onclick="return confirm('Are you sure you want to delete {{$transact->detail}}\'s Proforma Invoice?')">Delete</a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$transactions->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>

@endsection
