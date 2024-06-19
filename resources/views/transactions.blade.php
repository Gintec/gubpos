@extends('layouts.theme')
<style>
    th, td {
        padding: 2px !important;
        font-size: 12px !important;
    }

</style>

@section('content')
    @php $pagetype="report"; $modal="accounthead"; @endphp

    <h3 class="page-title">Financial | <small style="color: green">Transactions</small></h3>
    <div class="row">
            <div class="panel" style="width:100% !important; position: relative;">
                <div class="panel-heading" style="text-align: center">

                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#transaction"> <i class="fa fa-plus"></i> Add New</a>


                </div>
                <div class="panel-body">
                    <table class="table table-striped" style="width: 100%;" id="products">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Account Head</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Detail</th>
                                <th>From / To</th>
                                <th>Approved By / Entered By</th>
                                <th style="width: 10% !important;">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transact)

                                <tr>
                                    <td>{{$transact->id}}</td>
                                    <td>{{$transact->title}}</td>
                                    <td>{{number_format($transact->amount,2)}}</td>
                                    <td>{{number_format($transact->balance,2)}}</td>
                                    <td>{{$transact->accounthead->title}} <br> <small><i>{{$transact->accounthead->category}}</i></small></td>
                                    <td>{{$transact->dated}}</td>
                                    <td>{{strtoupper($transact->reference_no)}}</td>
                                    <td>{{$transact->detail}}</td>
                                    <td>{{is_numeric($transact->from)?$users->where('id',$transact->from)->first()->name:$transact->from}} / <br> {{is_numeric($transact->to)?$users->where('id',$transact->to)->first()->name:$transact->to}}</td>
                                    <td>{{is_numeric($transact->approved_by)?$users->where('id',$transact->approved_by)->first()->name:$transact->approved_by}} / <br> {{is_numeric($transact->recorded_by)?$users->where('id',$transact->recorded_by)->first()->name:$transact->recorded_by}}</td>
                                    <td>
                                        <button class="label label-primary" id="ach{{$transact->id}}" onclick="transaction({{$transact->id}})"  data-toggle="modal" data-target="#transaction" data-title="{{$transact->title}}" data-amount="{{$transact->amount}}" data-account_head="{{$transact->account_head}}" data-date="{{$transact->dated}}" data-reference_no="{{$transact->reference_no}}" data-detail="{{$transact->detail}}" data-from="{{$transact->from}}" data-to="{{$transact->to}}" data-approved_by="{{$transact->approved_by}}"  data-recorded_by="{{$transact->recorded_by}}">Edit</button>
                                        @if ($transact->account_head==1)
                                            <a href="{{url('/invoice/invoice/'.$transact->id)}}" target="_blank" class="label label-success">Invoice</a>
                                        @endif
                                        <a href="{{url('/invoice/receipt/'.$transact->id)}}" target="_blank" class="label label-warning">Reciept</a>
                                        <a href="{{url('/delete-trans/'.$transact->id)}}" class="label label-danger Super"  onclick="return confirm('Are you sure you want to delete {{$transact->detail}}\'s Financial Record?')">Delete</a>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td>#</td>
                                <td>Title</td>
                                <td>Amount</td>
                                <td>Balance</td>
                                <td>Account Head</td>
                                <td>Date</td>
                                <td>Invoice No</td>
                                <td>Detail</td>
                                <td>From / To</td>
                                <td>Approved By / Entered By</td>
                                <td>Action</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div style="text-align: right">
                        {{$transactions->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>


    <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal" id="transaction">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Transaction Form</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <form method="POST" action="{{ route('addtransaction') }}">
                @csrf
                <input type="hidden" name="id" id="0">
                <div class="row">
                    <div class="form-group col-md-6">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" value="0">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="date">Transaction Date</label>
                        <input type="text" name="date" id="date" class="form-control datepicker">
                    </div>


                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Payment for Pure Water">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="account_head"  class="control-label">Account Head</label>
                        <select class="form-control" name="account_head" id="account_head">

                            @foreach ($accountheads as $account)
                                <option value="{{$account->id}}">{{$account->title}} - ({{$account->category}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reference_no">Invoice/Job Number</label>
                    <select name="reference_no" id="reference_no" class="form-control select2" style="width: 100%">
                        <option value="">Select Invoice Number (optional)</option>
                        @foreach ($transactions->where('payment_status','!=','Returned') as $inv)
                            <option value="{{$inv->reference_no}}">{{$inv->title}} (Balance: {{$inv->balance}})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="detail">More Info</label>
                    <input type="text" name="detail" id="detail" class="form-control" placeholder="e.g. Check Number, Transfer, Teller no">
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="from"  class="control-label">From/Sender</label>

                        <select class="form-control" name="from" id="from">
                            <option value="Gubabi">Gubabi Management</option>
                            <option value="Others">Others</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="to"  class="control-label">To/Receiver</label>
                        <select class="form-control" name="to" id="to">
                            <option value="Gubabi">Gubabi Management</option>
                            <option value="Others">Others</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="approved_by"  class="control-label">Approved By</label>

                        <select class="form-control" name="approved_by" id="approved_by">
                            <option value="Gubabi">Gubabi Management</option>
                            <option value="Others">Others</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="recorded_by"  class="control-label">Delivered / Recorded By</label>
                        <select class="form-control" name="recorded_by" id="recorded_by">
                            <option value="Gubabi">Gubabi Management</option>
                            <option value="Others">Others</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save Transaction') }}
                    </button>
                </div>


            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>


@endsection
