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
    @php $pagename = "newsales"; @endphp

    @if(Session::get('tid'))
        @php
            $tid = Session::get('tid');
        @endphp
        <div class="row">
            <div class="col-md-3 col-md-offset-2">
                <a href="{{url('new-invoice/invoice/'.$tid)}}" class="btn btn-success" target="_blank">Print Invoice</a>
            </div>
            <div class="col-md-3 col-md-offset-2">
                <a href="{{url('new-invoice/receipt/'.$tid)}}" class="btn btn-primary" target="_blank">Print Receipt</a>
            </div>
            <div class="col-md-3 col-md-offset-2">
                <a href="{{url('invoice/delivery/'.$tid)}}" class="btn btn-primary" target="_blank">Delivery Note</a>
            </div>
        </div>
        <hr>
    @endif

    <h3 class="page-title">New Sales | <small style="color: green">Click to Select Items</small></h3>
    <div class="row">
            <div class="panel">

                <div class="panel-body">
                    <div class="col-md-4">
                        @foreach ($products as $product)
                            <a href="#" data-pid="{{$product->id}}" data-munit="{{$product->measurement_unit}}"  data-price="{{$product->price}}" data-in_stock="{{$product->stock->quantity}}" data-name="{{$product->name}}" onclick="addItem({{$product->id}})" id="item{{$product->id}}">
                                <div class="square bg img" style="background-image: url('{{asset('public/images/products/'.$product->picture)}}');">
                                    <div class="content">
                                        {{$product->name}}
                                        <br>N{{$product->price}}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="col-md-8" style="float: right;">
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

                                        <th style="width: 18%">Discount</th>
                                        <th style="width: 18%">Tax</th>
                                        <th style="width: 10%">Tax %</th>
                                        <th colspan="2" style="width: 18%">Total Amount to Pay</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control numberInput"   pattern="[0-9,]*" value="0" id="discount" name="discount" required></td>
                                        <td><input type="text" class="form-control numberInput"   pattern="[0-9,]*" value="0" step="0.01" id="tax" name="tax" required></td>
                                        <td><input type="number" class="form-control" value="7.5" step="0.01" id="tax_percent" name="tax_percent" required></td>
                                        <td colspan="2"><input type="text"   pattern="[0-9,]*" class="form-control numberInput" value="0" id="total_due" name="total_due" readonly></td>


                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align: right;"><b>Total Paid:</b> </td>
                                        <td><input type="text"  pattern="[0-9,]*" class="form-control numberInput" value="0" id="amount_paid" name="amount_paid"></td>

                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="text" class="form-control" name="details" placeholder="details e.g. Transaction Reference Number" required></td>
                                        <td><input type="text" class="form-control datepicker" value="{{date('Y-m-d')}}" name="dated_sold" placeholder="Date Sold" required></td>
                                        <td colspan="2">
                                            <input type="text" name="group_id" id="group_id" placeholder="Invoice Number" value="{{$lastInvoiceNo+1}}" class="form-control" required>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="form-group col-md-8" style="margin-top: 20px;">
                                    <input type="hidden" name="buyer" id="buyer">

                                            <select name="customer" id="select_customer" class="form-control">
                                                <option value="2" selected>Select Customer</option>
                                                <option value="New">New Customer</option>
                                                @foreach ($settings->personnel->where('category','Customer') as $cus)
                                                    <option value="{{$cus->id}}">{{$cus->name}}</option>
                                                @endforeach
                                            </select>
                                </div>

                                <div class="form-group col-md-4" style="margin-top: 20px;">
                                        <select class="form-control" name="pay_method" id="pay_method">
                                            <option value="Payment Method" selected>Payment Method</option>
                                            <option value="Cash">Cash</option>
                                            <option value="POS">POS</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Not Paid">Not Paid</option>
                                        </select>
                                </div>


                            </div>

                            <div id="customer_form">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="customer_name" placeholder="Customer Name" class="form-control">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <input type="text" name="phone_number" placeholder="Phone Number" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="address" placeholder="Delivery Address" class="form-control">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <input type="text" name="email" placeholder="Email Address" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="add_delivery">Add Delivery? </label>
                                    <input type="checkbox" value="Yes" name="add_delivery" id="add_delivery">
                                </div>

                                <div class="form-group col-md-3" id="dfee">
                                    <label for="delivery_fee">Delivery Fee: </label>
                                    <input type="delivery_fee" value="0" name="delivery_fee" class="form-control">
                                </div>

                                <div class="form-group col-md-6" style="float: right !important; margin-top: 20px;">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Checkout') }}
                                    </button>
                                </div>
                            </div>



                        </form>
                    </div>




                </div>
            </div>

    </div>

@endsection
