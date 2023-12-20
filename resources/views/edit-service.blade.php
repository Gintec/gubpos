@extends('layouts.theme')

@section('content')
    <h3 class="page-title">Add New Members </h3>

    <div class="panel">

        <div class="panel-body">
            <form action="{{ route('save-service') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$service->id}}">
                <div class="form-group">
                    <label for="customer">Customer Name: {{$service->user->name}}</label>
                    <input type="hidden" class="form-control" id="customer" name="customer" value="{{$service->customer}}" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{$service->start_date}}" >
                </div>

                <div class="form-group">
                    <label for="completion_date">Completion Date:</label>
                    <input type="text" class="form-control datepicker" id="completion_date" name="completion_date" value="{{$service->completion_date}}" >
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" name="category" id="category">
                        <option value="{{$service->category}}" selected>{{$service->category}}</option>
                        @foreach ($categories as $cat)
                            <option value="{{$cat->title}}">{{$cat->title}}</option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{$service->description}}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" id="status" name="status" maxlength="40" value="{{$service->status}}" >
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{$service->amount}}"">
                </div>

                <div class="form-group">
                    <label for="amountpaid">Amount Paid:</label>
                    <input type="number" class="form-control" id="amountpaid" name="amountpaid" value="{{$service->amountpaid}}"">
                </div>
                <label>Submit Form</label> <br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>
    </div>
@endsection
