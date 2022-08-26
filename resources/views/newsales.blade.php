@extends('layouts.theme')
<style>
    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2%;
    }

    .square {
    float:left;
    position: relative;
    width: 30%;
    padding-bottom : 30%; /* = width for a 1:1 aspect ratio */
    margin:1.66%;
    background-color:#fff;
    overflow:hidden;
}

.content {
    position:absolute;
    /* height:90%; /* = 100% - 2*5% padding */
    width:100%; /* = 100% - 2*5% padding */
    padding: 5%;
    margin-top: 80%;
    text-align: center;
    background-color: black;
    color: #fff !important;
    opacity: 0.8;
    bottom: 0px;
}

/*  For responsive images */

.content .rs{
    width:auto;
    height:auto;
    /* max-height:90%; */
    max-width:100%;

}
/*  For responsive images as background */

.bg{
    background-position:center center;
    background-repeat:no-repeat;
    background-size:cover; /* you change this to "contain" if you don't want the images to be cropped */
    color:#fff;
}

</style>
@section('content')
    @php $modal="production_jobs"; $pagename = "production_jobs"; @endphp

    <h3 class="page-title">New Sales | <small style="color: green">Click to Select Items</small></h3>
    <div class="row">
            <div class="panel">

                <div class="panel-body">
                    <div class="col-md-7" style="float: right;">
                        <form action="{{ route('addsales') }}" method="post" id="selecteditems">
                            @csrf
                            <table class="table" id="itemlist">
                                <thead>
                                    <tr class="spechead">
                                        <th style="width: 36%">Item</th>
                                        <th style="width: 18%">Quantity</th>
                                        <th style="width: 18%">Rate</th>
                                        <th style="width: 18%">Amount</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="item_list">


                                </tbody>
                            </table>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 36%">Total Amount</th>
                                        <th style="width: 18%">Total Paid</th>
                                        <th style="width: 18%">Discount</th>
                                        <th style="width: 18%">Tax</th>
                                        <th style="width: 10%">Tax %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="number" class="form-control" value="0" step="0.01" id="total_due" name="total_due" readonly></td>
                                        <td><input type="number" class="form-control" value="0" step="0.01" id="amount_paid" name="amount_paid"></td>
                                        <td><input type="number" class="form-control" value="0" step="0.01" id="discount" name="discount"></td>
                                        <td><input type="number" class="form-control" value="0" step="0.01" id="tax" name="tax"></td>
                                        <td><input type="number" class="form-control" value="7.5" step="0.01" id="tax_percent" name="tax_percent"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="text" class="form-control" name="details" placeholder="details e.g. on credit"></td>
                                        <td><input type="text" class="form-control datepicker" value="{{date('Y-m-d')}}" name="dated_sold" placeholder="Date Sold"></td>
                                        <td colspan="2">
                                            <input type="hidden" name="buyer" id="buyer">
                                            <input list="customers" class="form-control" name="customer" id="customer" placeholder="Customer Name">
                                            <datalist id="customers">
                                                @foreach ($settings->personnel->where('category','Customer') as $cus)
                                                    <option value="{{$cus->name}}">{{$cus->id}}</option>
                                                @endforeach
                                            </datalist>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="form-group col-md-6" style="margin-top: 20px;">
                                    <input type="text" name="group_id" id="group_id" placeholder="Invoice Number" class="form-control">
                                </div>

                                <div class="form-group col-md-6" style="float: right !important; margin-top: 20px;">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Checkout') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="col-md-5">
                        @foreach ($products as $product)
                            <a href="#" data-pid="{{$product->id}}" data-munit="{{$product->measurement_unit}}"  data-price="{{$product->price}}" data-in_stock="{{$product->stock->quantity}}" data-name="{{$product->name}}" onclick="addItem({{$product->id}})" id="item{{$product->id}}">
                                <div class="square bg img" style="background-image: url('{{asset('/images/products/'.$product->picture)}}');">
                                    <div class="content">
                                        {{$product->name}}
                                        <br>N{{$product->price}}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>


                </div>
            </div>

    </div>

@endsection
