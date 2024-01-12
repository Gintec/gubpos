@extends('layouts.theme')

@section('content')
    @php $modal="productsupplies"; $pagename = "productsupplies"; @endphp

    <h3 class="page-title">Product Supplies | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">

                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#psupply">Add New</a>


                </div>
                <div class="panel-body">
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <th>Product</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Cost Per Unit</th>
                                <th>Total Amount</th>
                                <th>Confirmed By</th>
                                <th>Recieved At</th>
                                <th>Batch No.</th>
                                <th>Date Supplied</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($psupplies as $sp)

                                <tr>

                                    <td><b>{{$sp->product->name}}</b></td>
                                    <td><b>{{$sp->supplier->supplier_name}} / {{$sp->supplier->company_name}}</b></td>
                                    <td>{{$sp->quantity}}</td>
                                    <td>{{$sp->cost_per}}</td>
                                    <td>{{$sp->total_amount}}</td>
                                    <td>{{$sp->confirmedby->name}}</td>
                                    <td>{{$sp->settings->business_name}}</td>
                                    <td>{{$sp->date_supplied}}</td>
                                    <td>

                                        <button class="label label-primary" id="ach{{$sp->id}}" onclick="psupply({{$sp->id}})"  data-toggle="modal" data-target="#psupply" data-supplier_id="{{$sp->supplier_id}}" data-product_id="{{$sp->product_id}}" data-quantity="{{$sp->quantity}}" data-cost_per="{{$sp->cost_per}}"  data-total_amount="{{$sp->total_amount}}" data-date_supplied="{{$sp->date_supplied}}" data-setting_id="{{$sp->setting_id}}" data-confirmed_by="{{$sp->confirmed_by}}"  data-batchno="{{$sp->batchno}}">Edit</button>
                                        <a href="{{url('/delete-ps/'.$sp->id)}}" class="label label-danger"  onclick="return confirm('Are you sure you want to delete the supply: {{$sp->product->name}}?')">Delete</a>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$psupplies->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>


    <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal" id="psupply">
    <div class="modal-dialog"  style="width: 90%">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add New Product Supply</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <form method="POST" action="{{ route('addpsupply') }}" id="psupplyform" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">

                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="product_id">product</label>
                        <select class="form-control" name="product_id" id="product_id">
                            @foreach ($settings->products as $sma)
                                <option value="{{$sma->id}}">{{$sma->name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control" name="supplier_id" id="supplier_id">
                            @foreach ($settings->suppliers as $ssu)
                                <option value="{{$ssu->id}}">{{$ssu->supplier_name}} ({{$ssu->company_name}})</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="batchno">Batch No</label>
                        <input type="text" name="batchno" id="batchno" class="form-control" placeholder="Reference, Invoice No etc">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="quantity">Quantity</label>
                        <input type="number" step="0.01" name="quantity" id="quantity" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cost_per">Cost Per Unit</label>
                        <input type="number" step="0.01" name="cost_per" id="cost_per" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="total_amount">Total Amount (Naira)</label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control">
                    </div>



                </div>

                <div class="row">

                    <div class="form-group col-md-4">

                        <label for="setting_id" class="control-label">Facility / Location</label>
                        <select class="form-control" name="setting_id" id="setting_id">
                            @foreach ($userbusinesses as $set)
                                <option value="{{$set->id}}">{{$set->business_name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="date_supplied">Date Supplied</label>
                        <input type="text" step="0.01" name="date_supplied" id="date_supplied" class="form-control datepicker">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="confirmed_by">Confirmed By</label>
                        <select class="form-control" name="confirmed_by" id="confirmed_by">
                            @foreach ($settings->personnel as $usr)
                                <option value="{{$usr->id}}">{{$usr->name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="pspbutton">
                        {{ __('Add Product Supply') }}
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
