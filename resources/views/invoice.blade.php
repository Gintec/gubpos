@extends('layouts.print-theme')
<style>
    th, td {
        padding: 4px !important;
    }

</style>
@section('content')
    @php
        $locale = 'en_US';
        $fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
    @endphp

            <h4 style="margin-left: 42%; color: white; background-color: darkBlue; width: 80px; padding: 5px; font-weight: bold; text-align: center;">
                {{ucwords($category)}}
            </h4>

        <small style="float: right; font-weight: bold;">{{ucwords($category)}} No.: {{strtoupper($sale->reference_no)}} | <i style="color: green"> {{$sale->payment_status}}</i></small>

    <div class="row" style="margin-top: -10px;">
            <div class="panel">
                <div class="panel-body">
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
                                    <td colspan="2"><b>Address:</b> {{$customer->address}}</th>
                                    <td><b>Phone Number:</b> {{$customer->phone_number}}</th>
                                </tr>
                        </tbody>
                    </table>
                    <hr>

                    <h4>Products Details</h4>
                    <table border="1" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Rate</th>
                                <th>Amount (<s>N</s>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $pr)
                                <tr>
                                    <td>{{$pr->product->type}} {{$pr->product->name}}</td>
                                    <td>{{$pr->quantity}}</td>
                                    <td>{{number_format($pr->price,2)}}</td>
                                    <td>{{number_format($pr->amount_paid,2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" style="text-align: right;">Vat:</td><td>{{number_format($sale->vat,2)}}</td>
                            </tr>
                            @if ($sale->discount>0)
                                <tr>
                                    <td colspan="3" style="text-align: right;">Discount:</td><td>{{number_format($sale->discount,2)}}</td>
                                </tr>
                            @endif
                            @if ($sale->balance>0)
                                <tr>
                                    <td colspan="3" style="text-align: right;">Balance:</td><td>{{number_format($sale->balance,2)}}</td>
                                </tr>
                            @endif

                            @if (isset($sale->delivery) && $sale->delivery->amount>0)
                                <tr>
                                    <td colspan="3" style="text-align: right;">Delivery Fee:</td><td>{{number_format($sale->delivery->amount,2)}}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight:bold;">
                                    Total Amount:
                                </td>
                                <td style="font-weight:bold;">
                                    {{number_format($sale->amount,2)}}
                                </td>

                            </tr>

                        </tbody>
                    </table>
                    <p>Amount In Words:
                        <b>
                            @php
                                if (strpos($sale->amount, '.') !== false) {
                                    $amountarray = explode(".",floatval($sale->amount));
                                    if(strlen($amountarray[1])==1){
                                        $amountarray[1]=$amountarray[1]*10;
                                    }
                                    if($amountarray[1]>0){
                                        if(isset($amountarray[0])){
                                            echo ucwords(numfmt_format($fmt, $amountarray[0]))." Naira ".ucwords(numfmt_format($fmt, $amountarray[1]))." Kobo";
                                        }
                                    }
                                }else{
                                    echo ucwords(numfmt_format($fmt, $sale->amount))." Naira Only";
                                }
                            @endphp
                        </b>
                    </p>
                    <hr>
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <th>Manager Sign</th>

                                <th style="text-align: right;">Customer Signature</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><br><br>______________________________</td>
                                <th  style="text-align: right;"><br><br>______________________________</th>
                            </tr>
                        </tbody>
                    </table>
                    @if ($category!="invoice")

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
