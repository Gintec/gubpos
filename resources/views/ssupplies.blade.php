@extends('layouts.theme')

@section('content')
    @php $modal="material"; $pagename = "materials"; @endphp

    <h3 class="page-title">Accessories/Parts Supplies | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">
                        <a href="{{url('spareparts')}}" class="btn btn-primary pull-right" data-toggle="modal" data-target="#supply" id="supplyupdate">Add New</a>
                </div>
                <div class="panel-body">
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <th>Batch No.</th>
                                <th>Accessory/Part</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Recorded By</th>
                                <th>Date Supplied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplies as $sp)
                                <tr>
                                    <td><b>{{$sp->batchno}}</b></td>
                                    <td><b>{{$sp->part->spareparts}}</b></td>
                                    <td><b>{{$sp->suppliedBy->supplier_name}}</b></td>
                                    <td>{{$sp->quantity}}</td>

                                    <td>{{$sp->recorded_by}}</td>
                                    <td>{{$sp->date_supplied}}</td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$supplies->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>




@endsection
