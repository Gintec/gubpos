@extends('layouts.theme')

@section('content')
    @php $pagetype="report"; $modal="accounthead"; @endphp

    <h3 class="page-title">Invoices | <small style="color: green">All Customer Invoices</small></h3>
    <div class="row">
            <div class="panel" style="width:100%">
                <div class="panel-body">

                        <h3>Invoice Report From: {{$from}} To: {{$to}}</h3>
                        <table class="table  responsive-table" style="width: 100%; position: relative" id="products">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Recorded By</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transact)

                                <tr>
                                    <td>{{$transact->customer->name}}</td>
                                    <td>{{$transact->title}}</td>
                                    <td>{{number_format($transact->amount,2)}}</td>
                                    <td>{{$transact->dated}}</td>
                                    <td>{{strtoupper($transact->reference_no)}}</td>

                                    <td>{{is_numeric($transact->recorded_by)?$users->where('id',$transact->recorded_by)->first()->name:$transact->recorded_by}}</td>
                                    <td>
                                            <a href="{{url('/invoice/invoice/'.$transact->id)}}" target="_blank" class="label label-success">Invoice</a>
                                            <a href="{{url('/invoice/receipt/'.$transact->id)}}" target="_blank" class="label label-warning">Reciept</a>
                                            <a href="{{url('/edit-invoice/'.$transact->id)}}" target="_blank" class="label label-primary">Edit</a>


                                        <a href="{{url('/delete-trans/'.$transact->id)}}" class="label label-danger Super"  onclick="return confirm('Are you sure you want to delete {{$transact->title}}\'s Financial Record?')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

@endsection
