@extends('layouts.theme')

@section('content')
    @php $pagetype="report"; $pagename = "products"; @endphp

    <h3 class="page-title">Accessories/Spareparts | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">

                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#product">Add New</a>


                </div>
                <div class="panel-body">
                    <table class="table responsive-table" id="products"  style="width: 100%;">
                        <thead>
                            <tr style="color: ">

                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock Bal.</th>
                                <th>Add To Stock</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($spareparts as $pr)

                                <tr>

                                    <td>{{$pr->spareparts}}</td>
                                    <td>{{$pr->price}}</td>
                                    <td>{{$pr->stock->quantity}}</td>
                                    <td>
                                        <form action="sSupplies" method="post">
                                            @csrf
                                            <input type="hidden" name="spart_id" value="{{$pr->id}}">
                                            <table>
                                                <tr>
                                                    <td class="form-group">
                                                        <select class="form-control" name="supplier_id" id="supplier_id">
                                                            <option value="">Select Supplier</option>
                                                            @foreach ($settings->suppliers as $ssu)
                                                                <option value="{{$ssu->id}}">{{$ssu->supplier_name}} ({{$ssu->company_name}})</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity" class="form-control" placeholder="Quantity">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="date_supplied" class="form-control datepicker" placeholder="Date Supplied">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="batchno" class="form-control" placeholder="Batch/Ref">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary">Add</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>


                                    </td>

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
