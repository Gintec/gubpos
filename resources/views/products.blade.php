@extends('layouts.theme')

@section('content')
    @php $modal="product"; $pagename = "products"; @endphp

    <h3 class="page-title">Products | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">

                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#product">Add New</a>


                </div>
                <div class="panel-body">
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <th>Image</th>
                                <th>Name</th>
                                <th>type/Category</th>
                                <th>Size/Unit</th>
                                <th>Price</th>
                                <th>Location</th>
                                <th>Stock Bal.</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $pr)

                                <tr>
                                    <td width="10%">
                                            <img src="{{asset('public/images/products/'.$pr->picture)}}" class="img-circle" alt="{{$settings->business_name}}" width="100%" height="auto">
                                    </td>
                                    <td><a href="{{url('/product/'.$pr->id)}}" target="_blank"><b>{{$pr->name}}</b></a></td>
                                    <td><b>{{$pr->type}}/{{$pr->category}}</b></td>
                                    <td>{{$pr->size}} {{$pr->measurement_unit}}</td>
                                    <td>{{$pr->price}}</td>
                                    <td>{{$pr->settings->business_name}}</td>
                                    <td>{{$pr->stock->quantity}}</td>
                                    <td>

                                        <button class="label label-primary" id="ach{{$pr->id}}" onclick="product({{$pr->id}})"  data-toggle="modal" data-target="#product" data-name="{{$pr->name}}" data-type="{{$pr->type}}" data-category="{{$pr->category}}" data-measurement_unit="{{$pr->measurement_unit}}"  data-picture="{{$pr->picture}}"  data-size="{{$pr->size}}"  data-price="{{$pr->price}}"  data-setting_id="{{$pr->setting_id}}">Edit</button>
                                        <a href="{{url('/delete-prd/'.$pr->id)}}" class="label label-danger"  onclick="return confirm('Are you sure you want to delete the product: {{$pr->name}}?')">Delete</a>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$products->links("pagination::bootstrap-4")}}
                    </div>
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
                            <option value="GUBABI FIREPROOF SAFE" selected>GUBABI FIREPROOF SAFE</option>
                            <option value="GUBABI FIREPROOF CABINET">GUBABI FIREPROOF CABINET</option>
                            <option value="MANUAL COMBINATION">MANUAL COMBINATION</option>
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
