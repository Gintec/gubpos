@extends('layouts.theme')

@section('content')
    @php $modal="product"; $pagename = "products"; @endphp

    <h3 class="page-title">Item Deliveries | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">

                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#product">Add New</a>


                </div>
                <div class="panel-body">
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Invoice No</th>
                                <th>Amount</th>
                                <th>Action</th>
                                <th>Print</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deliveries as $deliv)

                                <tr>

                                    <td>{{$deliv->Customer->name}}</td>
                                    <td>{{$deliv->delivery_address}}</td>
                                    <td>{{$deliv->transaction->reference_no}}</td>
                                    <th>{{$deliv->amount}}</th>
                                    <td>
                                        @php
                                            $readonly = "";
                                            if($deliv->status=="Delivered"){
                                                $readonly = "readonly";
                                            }
                                        @endphp
                                        @if($readonly=="")
                                        <form action="{{route('saveDelivery')}}" method="post">
                                            @csrf
                                        @endif
                                            <input type="hidden" name="id" value="{{$deliv->id}}">
                                            <table>
                                                <tr class="form-group">
                                                    <td>
                                                        <select class="form-control" name="delivered_by" id="delivered_by" {{$readonly}}>
                                                            @if ($deliv->deliveredBy!="")
                                                                <option value="{{$deliv->deliveredBy}}" selected>{{$deliv->DeliveredBy->name}}</option>
                                                            @else
                                                                <option value="" selected>Select Delivery Person</option>
                                                            @endif

                                                            @foreach ($deliverymen as $dm)
                                                                <option value="{{$dm->id}}">{{$dm->name}} ({{$dm->about}})</option>
                                                            @endforeach

                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="details" class="form-control" placeholder="Recieved By" value="{{$deliv->details}}" {{$readonly}}>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="delivery_date" class="form-control datepicker" placeholder="Date" value="{{$deliv->delivery_date}}" {{$readonly}}>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="status" id="status" {{$readonly}}>
                                                            @if ($deliv->status!="")
                                                                <option value="{{$deliv->status}}">{{$deliv->status}}</option>
                                                            @else
                                                                <option value="">Status</option>
                                                            @endif
                                                            <option value="Delivered">Delivered</option>
                                                            <option value="Other Issues">Other Issues</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success" {{$readonly}}>Save</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        @if($readonly=="")
                                        </form>
                                        @endif


                                    </td>
                                    <td><a href="{{url('invoice/delivery/'.$deliv->invoice_no)}}" class="btn btn-primary" target="_blank">View</a></td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

    </div>


    <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal" id="product">
    <div class="modal-dialog"  style="width: 90%">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add New product</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <form method="POST" action="{{ route('addproduct') }}" id="productform" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">

                <div class="row">

                    <div class="form-group col-md-8">
                        <label for="name">Item Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="price">Cost Per Unit</label>
                        <input type="text" name="price" id="price" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="type">Type</label>
                        <select class="form-control" name="type" id="type">
                         <option value="Safes">Safes</option>
                         <option value="Cabinets">Cabinets</option>
                         <option value="Burglary Safes">Burglary Safes</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="size">Size</label>
                        <input type="text" name="size" id="size" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="measurement_unit">Measurement Unit</label>
                        <input type="text" name="measurement_unit" id="measurement_unit" class="form-control">
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="category">Category</label>
                        <select class="form-control" name="category" id="category">
                        <option value="Upcoming">Upcoming</option>

                        </select>
                    </div>

                    <div class="form-group col-md-6">

                        <label for="setting_id" class="control-label">Facility / Location</label>
                        <select class="form-control" name="setting_id" id="setting_id">
                            <option value="1" selected>Select Location</option>
                            @foreach ($userbusinesses as $set)
                                <option value="{{$set->id}}">{{$set->business_name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <input type="hidden" id="oldpicture" name="oldpicture">
                    <label for="picture">Upload Featured Image</label>
                    <input type="file" name="picture" id="picture" class="form-control">
                </div>






                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="prdbutton">
                        {{ __('Add Product') }}
                    </button>
                </div>


            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger modaldismiss" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>


@endsection
