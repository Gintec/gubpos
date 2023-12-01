@extends('layouts.theme')

@section('content')
    @php $modal="material"; $pagename = "Sales Report"; $pagetype="report"; @endphp

    <h3 class="page-title">Sales Record | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">
                        <div style="text-align: center;">
                            <a href="{{url('/newsales')}}" class="btn btn-success"><i class="lnr lnr-cart"></i> Add New Sales</a>
                        </div>
                </div>
                <div class="panel-body">
                    <table class="table responsive-table" id="products" style="width: 100%;">
                        <thead>
                            <tr style="color: ">
                                <th>Invoice No</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price Per</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Seller</th>
                                <th>Confirmed By</th>
                                <th>Date Sold</th>
                                <th>Details</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)

                                <tr>
                                    <td>{{$sale->group_id}}</td>
                                    <td>{{$sale->product->name}}</td>
                                    <td><b>{{$sale->quantity}}</b></td>
                                    <td><b>{{number_format($sale->price,2)}}</b></td>
                                    <td>{{number_format($sale->amount_paid,2)}}</td>
                                    <td>{{$sale->customer->name}}</td>
                                    <td>{{$sale->seller->name}}</td>
                                    <td>{{$sale->confirmedby->name}}</td>
                                    <td>{{$sale->dated_sold}}</td>
                                    <td>{{$sale->detail}}</td>
                                    <td>
                                        <a href="{{url('/delete-sale/'.$sale->id)}}" class="label label-danger" onclick="return confirm('Are you sure you want to delete the Sales checkout record, this will return the {{$sale->product->name}} with quantity {{$sale->quantity}} back to stock?')">Delete</a>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$sales->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>

@endsection
