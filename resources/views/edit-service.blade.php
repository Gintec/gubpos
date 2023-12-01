@extends('layouts.theme')

@section('content')
    <h3 class="page-title">Add New Members </h3>

    <div class="panel">

        <div class="panel-body">
            <form action="{{ route('save-service') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_id">User ID:</label>
                    <input type="number" class="form-control" id="user_id" name="user_id" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label for="completion_date">Completion Date:</label>
                    <input type="date" class="form-control" id="completion_date" name="completion_date" required>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" class="form-control" id="category" name="category" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" class="form-control" id="status" name="status" maxlength="40" required>
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>
    </div>
