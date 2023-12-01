@extends('layouts.theme')

@section('content')
    <h3 class="page-title">Add New Members </h3>

    <div class="panel">

        <div class="panel-body">
            <form action="{{ route('save-service') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group">
                        <div class="form-group col-md-6">
                            <label for="customer"  class="control-label sr-only">Customer Name</label>
                            <select class="form-control" name="customer" id="customer">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="completion_date">Completion Date:</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="category">Category:</label>
                        <input type="text" class="form-control" id="category" name="category" maxlength="50" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="status">Status:</label>
                        <input type="text" class="form-control" id="status" name="status" maxlength="40" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="amount">Amount:</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                    </div>

                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </div>





            </form>

        </div>
    </div>
@endsection
