@extends('layouts.theme')

@section('content')
    <h3 class="page-title">Returned Item Form</h3>
    <h3>ITEM NAME: {{$item->product->name}}</h3>

    <div class="panel">
        <div class="panel-body">
            <form action="{{ route('saveReturned') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{$item->id}}">
                <div class="row">
                    <div class="col-md-6">

                        <div class="row">
                            <div class="form-group">
                                <div class="form-group col-md-6">
                                    <label for="returnedby"  class="control-label">Who Returned It</label>
                                    <input type="text" class="form-control" id="returnedby" name="returnedby" >

                                </div>

                                <div class="form-group col-md-2">
                                    <label for="quantity">Quantity Returned:</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" >
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="typeofreturn">Type of Return:</label>
                                    <select class="form-control" name="typeofreturn" id="typeofreturn">
                                        <option value="Defect">Defect</option>
                                        <option value="Change">For Changing</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="date_returned">Date Returned:</label>
                                <input type="text" class="form-control datepicker" id="date_returned" name="date_returned" >
                            </div>
                            <div class="form-group col-md-9">
                                <label for="reason">Reason for Return:</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="returnedto">Return to:</label>
                                <select class="form-control" name="returnedto" id="returnedto">
                                   <option value="Stock" selected>Stock</option>
                                   <option value="Others">Others</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount">Amount to be Deducted From Sale:</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="0">
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">Save Returned Item</button>
                            </div>

                        </div>

                    </div>


                </div>





            </form>

        </div>
    </div>
@endsection
