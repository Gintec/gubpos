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
        </div>
        <hr>
    @endif

    <h3 class="page-title">Edit Sales | <small style="color: green">Click to Select Items</small></h3>
    <div class="row">
            <div class="panel">

                <div class="panel-body">

                    <div class="col-md-7" style="float: right;">
                        <form action="{{ route('update-invoice') }}" method="post" id="selecteditems">
                            @csrf
                            <input type="hidden" name="id" value="{{$trans->id}}">
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
                                    @foreach ($psales as $pr)


                                    <tr id='itrow{{$pr->product_id}}'>
                                        <td class='form-group'>
                                            <input name="pid[]" value="{{$pr->id}}" type="hidden">
                                            <input id='item{{$pr->product_id}}' type='hidden' name='product_id[]' class='form-control' value='{{$pr->product_id}}' readonly>
                                            <h5 id='itname{{$pr->product->id}}'>{{$pr->product->name}}</h5>
                                            <small><i>(Stock: {{$pr->product->stock->quantity}})</i></small>
                                        </td><td class='form-group'><input id='qty{{$pr->product_id}}'  onchange='changeQty({{$pr->product_id}})' type='number' value='1' name='qty[]' class='form-control quantity'><span><small>{{$pr->product->measurement_unit}}</small></span></td><td class='form-group'><input id='unit{{$pr->product_id}}' type='number' onchange='changeUc({{$pr->product_id}})' name='unit[]'  value='{{$pr->product->price}}' class='form-control'></td><td class='form-group'><input id='amount{{$pr->product_id}}' type='number' name='amount[]'  value='{{$pr->product->price}}' class='form-control amount' readonly></td><td class='form-group'><a href='#' class='badge badge-danger removeitem' id='re{{$pr->product_id}}'>X</a></td>
                                    </tr>
                                    @endforeach

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
                                        <td><input type="number" class="form-control" value="{{$trans->amount+$trans->balance}}" id="total_due" name="total_due" readonly></td>
                                        <td><input type="number" class="form-control" value="{{$trans->amount}}" id="amount_paid" name="amount_paid"></td>
                                        <td><input type="number" class="form-control" value="{{$trans->discount}}" id="discount" name="discount"></td>
                                        <td><input type="number" class="form-control" value="{{$trans->vat}}" step="0.01" id="tax" name="tax"></td>
                                        <td><input type="number" class="form-control" value="{{$trans->vat/($trans->amount+$trans->balance)*100}}" step="0.01" id="tax_percent" name="tax_percent"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="text" class="form-control" name="details" placeholder="details e.g. on credit" value="{{$trans->detail}}"></td>
                                        <td><input type="text" class="form-control datepicker" value="{{date('Y-m-d')}}" name="dated_sold" placeholder="Date Sold"></td>
                                        <td colspan="2">
                                            <input type="text" name="group_id" id="group_id" placeholder="Invoice Number" class="form-control" value="{{$trans->reference_no}}" required>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="form-group col-md-8" style="margin-top: 20px;">
                                    <input type="hidden" name="buyer" id="buyer" value="{{$trans->from}}">
                                            <input list="customers" class="form-control" name="customer" id="customer" placeholder="Customer Name" value="{{$trans->customer->name}}">
                                            <datalist id="customers">
                                                @foreach ($settings->personnel->where('category','Customer') as $cus)
                                                    <option value="{{$cus->name}}">{{$cus->id}}</option>
                                                @endforeach
                                            </datalist>
                                </div>

                                <div class="form-group col-md-4" style="margin-top: 20px;">
                                        <select class="form-control" name="pay_method" id="pay_method">
                                            <option value="{{$trans->payment_type}}" selected>Payment Method</option>
                                            <option value="Cash">Cash</option>
                                            <option value="POS">POS</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Not Paid">Not Paid</option>
                                        </select>
                                </div>

                                <div class="row">
                                    @if ($trans->payment_status=="Proforma")
                                        <div class="form-group col-md-4" style="margin-top: 20px;">
                                            <select class="form-control" name="convert" id="convert">
                                                <option value="" selected>Convert to Invoice?</option>
                                                <option value="Yes">Yes, Make Invoice</option>
                                                <option value="No">Proforma</option>

                                            </select>
                                        </div>
                                    @endif



                                    <div class="form-group col-md-6" style="float: right !important; margin-top: 20px;">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Update') }}
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </form>
                    </div>

                    <div class="col-md-5">
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


                </div>
            </div>

    </div>

@endsection
