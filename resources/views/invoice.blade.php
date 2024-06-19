@extends('layouts.print-theme')
<style>
    th, td {
        padding: 1px !important;
        font-size: 12px !important;
    }

</style>

@if ($category!="receipt")
    <style>
        .content {
            min-width: 50%;
            width: auto;
            border-radius: 10px;
            border: 4px solid #2E2C46;
            padding: 20px;
            margin: 20px auto; /* Center the box on the page */
        }
    </style>
@endif
@section('content')
    @php
        $locale = 'en_US';
        $fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);

    @endphp
        @if ($category!="delivery")
            <h4 style="margin-left: 42%; color: white; background-color: darkBlue; width: 80px; padding: 5px; font-weight: bold; text-align: center;">
                {{ucwords($category)}}
            </h4>
        @else
            <h4 style="margin-left: 35%; color: white; background-color: darkBlue; width: 160px; padding: 5px; font-weight: bold; text-align: center;">
                Delivery Note
            </h4>
        @endif

        @if ($category!="receipt")
            <small style="float: right; font-weight: bold;">{{ucwords($category)}} No.: {{strtoupper($sale->reference_no)}}
                | <i style="color: green"> {{$sale->payment_status}}</i>
            </small>
        @else
            <h4 style="text-align: center; font-weight: bold; padding: 1px;">{{ucwords($category)}} No.: {{strtoupper($sale->reference_no)}}
            </h4>
        @endif

    <div class="row" style="margin-top: -10px;">
            <div class="panel">
                <div class="panel-body">
                    @if ($category!="receipt")
                        <h4>Customer Detail</h4>
                        <table border="1" style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="2">Name: {{$customer->name}}</th>
                                    @php
                                        $timestamp = strtotime($sale->dated);
                                    @endphp
                                    <th>Date: {{date('jS F, Y', $timestamp)}}</th>
                                </tr>

                            </thead>
                            <tbody>

                                    <tr>
                                        <td colspan="2"><b>
                                            @if ($category=="delivery")
                                                Delivery
                                            @endif
                                            Address:</b> {{$customer->address}}</th>
                                        <td><b>Phone Number:</b> {{$customer->phone_number}}</th>
                                    </tr>
                            </tbody>
                        </table>
                        @if ($category!="delivery")
                            <hr>
                            <h4>Products Details</h4>
                        @endif
                        <table border="1" style="width: 100%; margin-top: 10px;">
                            <thead>
                                <tr>
                                    @if ($category=="delivery")
                                        <th><img  src="{{asset('/public/assets/img/check.png')}}" width="12" height="12"></th>
                                    @endif

                                    <th>Description</th>
                                    <th>Quantity</th>
                                    @if ($category=="delivery")
                                        <th style="width: 30px !important">Qty. Received</th>
                                    @endif
                                    @if ($category!="delivery")
                                        <th>Unit Rate</th>
                                        <th>Amount (<s>N</s>)</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $pr)
                                    <tr>
                                        @if ($category=="delivery")
                                            <td></td>
                                        @endif
                                        <td>{{$pr->product->name}}</td>
                                        <td>{{$pr->quantity}}</td>
                                        @if ($category=="delivery")
                                            <td></td>
                                        @endif
                                        @if ($category!="delivery")
                                            <td>{{number_format($pr->price,2)}}</td>
                                            <td>{{number_format($pr->amount_paid,2)}}</td>
                                        @endif
                                    </tr>
                                @endforeach

                                @if ($category!="delivery")

                                    @if ($sale->discount>0)
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Discount:</td><td>{{number_format($sale->discount,2)}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" style="text-align: right;">Vat:</td><td>{{number_format($sale->vat,2)}}</td>
                                    </tr>


                                    @if (isset($sale->delivery) && $sale->delivery->amount>0)
                                        <tr>
                                            <td colspan="3" style="text-align: right;">Delivery Fee:</td><td>{{number_format($sale->delivery->amount,2)}}</td>
                                        </tr>
                                    @endif


                                        <tr>
                                            <td colspan="3" style="text-align: right; font-weight:bold;">
                                                Grand Total Amount:
                                            </td>
                                            <td style="font-weight:bold;">
                                                {{number_format($sale->amount,2)}}
                                            </td>

                                        </tr>
                                    @if ($category!="proforma")
                                        <tr>
                                            <td colspan="3" style="text-align: right; font-weight:bold;">
                                                Total Amount Paid:
                                            </td>
                                            <td style="font-weight:bold;">
                                                {{number_format($sale->amount-$sale->balance,2)}}
                                            </td>

                                        </tr>

                                        @if ($sale->balance>0)
                                            <tr>
                                                <td colspan="3" style="text-align: right;">Balance:</td><td>{{number_format($sale->balance,2)}}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endif

                            </tbody>
                        </table>
                    @endif

                    @if ($category=="receipt")
                        <p style="float: right; text-align: right; font-weight: bold; clear: both">
                            @php
                                $timestamp = strtotime($sale->dated);
                            @endphp
                            Date: {{date('jS F, Y', $timestamp)}}
                        </p>
                        <p>Recieved from <b>{{$customer->name}}</b></p>
                        <hr>
                    @endif
                    @if ($category!="delivery")
                        <p>Amount In Words:
                            <b>
                                @php
                                    if (strpos($sale->amount-$sale->balance, '.') !== false) {
                                        $amountarray = explode(".",floatval($sale->amount-$sale->balance));
                                        if(strlen($amountarray[1])==1){
                                            $amountarray[1]=$amountarray[1]*10;
                                        }
                                        if($amountarray[1]>0){
                                            if(isset($amountarray[0])){
                                                echo ucwords(numfmt_format($fmt, $amountarray[0]))." Naira ".ucwords(numfmt_format($fmt, $amountarray[1]))." Kobo";
                                            }
                                        }
                                    }else{
                                        echo ucwords(numfmt_format($fmt, $sale->amount-$sale->balance))." Naira Only";
                                    }
                                @endphp
                            </b>
                        </p>

                    @endif
                    @if ($category=="receipt")
                    <p>In Settlement of Invoice No: {{strtoupper($sale->reference_no)}}</p>
                    @endif

                    @if ($category=="delivery")
                        <small><i>Please, identify the items recieved by ticking the checkmark and the quantity received</small></i>
                        <p><small><i>Above goods were checked and received in good condition, as ordered.</i></small><br>
                            <small><i>Goods Supplied in Good condition are not returnable.</i></small>
                        </p>
                        <div class="row">

                                <table style="width: 100%; border: 1; font-weight: bold;">
                                    <tr>
                                        <td>Driver's Name:__________________________</td>

                                        <td>Receiver's Name:________________________</td>

                                    </tr>
                                    <tr>
                                        <td>Driver's Phone:_________________________</td>

                                        <td>Receiver's Phone:_______________________</td>

                                    </tr>
                                    <tr>
                                        <td>Signature:_____________________________</td>

                                        <td>Signature:_____________________________</td>

                                    </tr>
                                    <tr>
                                        <td>Date:__________________________________</td>

                                        <td>Date:__________________________________</td>


                                    </tr>
                                </table>

                        </div>
                    @endif
                    <hr>
                    @if ($category!="delivery")
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th><br><br>______________________________</th>
                                    <th  style="text-align: right;"><br><br>______________________________</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Customer's Signature</td>

                                    <td style="text-align: right;">For: GUBABI & CO. LTD</td>

                                </tr>
                            </tbody>
                        </table>
                    @endif
                    @if ($category!="invoice" && $category!="receipt"  && $category!="delivery")

                        <table class="table responsive-table">
                            <thead>
                                <tr style="color: ">
                                    <th colspan="3">Bank Name: Gubabi & Co. Ltd</th>

                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Zenith bank <br>
                                        1011754920
                                    </td>
                                    <td>Access Bank <br>
                                        0015230510
                                    </td>
                                    <td>
                                        Polaris bank <br>
                                        4090772091
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

    </div>


@endsection
