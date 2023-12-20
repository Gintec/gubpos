@extends('layouts.print-theme')

@section('content')
    @php
        $locale = 'en_US';
        $fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
    @endphp
    <h4 class="page-title" style="font-weight: bold; text-align: center;">{{ucwords($category)}} No.: {{strtoupper($sale->reference_no)}} </h4>
    <div class="justify-content-end">
        <small style="color: green"><i>{{$sale->pay_status}}</i></small>
    </div>
    <div class="row" style="margin: 10px auto;">

            <div class="panel">
                <div class="panel-body">
                    <h4>Customer Detail</h4>
                    <table class="table responsive-table">
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
                    <table class="table responsive-table table-striped" style="padding: 0px;">
                        <thead>
                            <tr style="color: ">
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
                                    <td>{{$pr->price}}</td>
                                    <td>{{$pr->amount_paid}}</td>
                                    <td>{{$pr->dated}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" style="text-align: right;">Vat:</td><td>{{$sale->vat}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right;">Discount:</td><td>{{$sale->discount}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right;">Balance:</td><td>{{$sale->balance}}</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    Total Amount:
                                </td>
                                <td>
                                    {{$sale->amount}}
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
