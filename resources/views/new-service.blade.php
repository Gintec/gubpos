@extends('layouts.theme')

@section('content')
    <h3 class="page-title">Create New Service </h3>

    <div class="panel">
        <div class="panel-body">
            <form action="{{ route('save-service') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <h3>Basic Questions</h3>
                                <table class="table table-striped">
                                    <tr>
                                        <th>Part Name/Answer</th>
                                    </tr>
                                @foreach ($categories->where('category_group','Technical Questions') as $question)
                                    <tr>
                                        <td>{{$question->title}} <br>
                                        <input type="text" class="form-control" name="answer{{$question->id}}"></td>
                                    </tr>
                                @endforeach
                                </table>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="form-group col-md-8">
                                    <label for="customer"  class="control-label">Customer Name</label>
                                    <select class="form-control" name="customer" id="customer">
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="category">Service Category:</label>
                                    <select class="form-control" name="category" id="category">
                                        @foreach ($categories as $cat)
                                            <option value="{{$cat->title}}">{{$cat->title}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date:</label>
                                <input type="text" class="form-control datepicker" id="start_date" name="start_date" >
                            </div>

                            <div class="form-group col-md-6">
                                <label for="completion_date">Estimated Completion Date:</label>
                                <input type="text" class="form-control datepicker" id="completion_date" name="completion_date" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">Customer's Request:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">Service to Render:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="status">Status:</label>
                                <select class="form-control" name="status" id="status">
                                   <option value="Quotation" selected>Quotation</option>
                                   <option value="Inspection">Inspection</option>
                                   <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount">Total Amount:</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="amountpaid">Amount Paid:</label>
                                <input type="number" class="form-control" id="amountpaid" name="amountpaid" value="0">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Submit Form</label> <br>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-6">
                        <h3>Select Spare-parts and Quantities</h3>
                        <table border="1">
                            <tr>
                                <th style="width: 80% !important">Part Name</th>
                                <th style="width: 20% !important">Quantity</th>
                            </tr>
                        @foreach ($spareparts as $spare)
                            <tr>
                                <td>{{$spare->spareparts}}</td>
                                <td><input type="number" name="quantity{{$spare->id}}" value="0"></td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                </div>





            </form>

        </div>
    </div>
@endsection
