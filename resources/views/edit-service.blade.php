@extends('layouts.theme')
@php $pagename = "service"; @endphp
@section('content')
    <h3 class="page-title">Add New Members </h3>

    <div class="panel">

        <div class="panel-body">
            <form action="{{ route('save-service') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$service->id}}">

                <div class="row">
                    <div class="col-md-6">

                        <div class="row">
                            <h3>Technical Questions</h3>
                                <table class="table table-striped">
                                    <tr>
                                        <th>Compulsory service questions</th>
                                    </tr>
                                    @foreach ($categories->where('category_group','Technical Questions') as $question)
                                        <tr>
                                            <td>{{$question->title}} <br>
                                                @php
                                                $quest = $service->questions->where('question_no', $question->id)->first();

                                                // Check if $question is not null before accessing the "answer" field
                                                if ($quest) {
                                                    $answer = $quest->answer;

                                                } else {
                                                    $answer = "";
                                                }
                                                @endphp
                                            <input type="text" class="form-control" name="answer{{$question->id}}" value="{{$answer}}"></td>
                                        </tr>
                                    @endforeach
                                </table>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="form-group col-md-8">
                                    <label for="customer"  class="control-label">Customer Name</label>
                                    <select class="form-control" name="customer" id="customer">
                                        <option value="{{$service->user->id}}" selected>{{$service->user->name}}</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="category">Service Category:</label>

                                    <select class="form-control" name="category" id="category">
                                        <option value="{{$service->category}}">{{$service->category}}</option>
                                        @foreach ($categories->where('category_group','Service Category') as $cat)
                                            <option value="{{$cat->title}}">{{$cat->title}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date:</label>
                                <input type="text" class="form-control datepicker" value="{{$service->start_date}}" id="start_date" name="start_date" >
                            </div>

                            <div class="form-group col-md-6">
                                <label for="completion_date">Estimated Completion Date:</label>
                                <input type="text" class="form-control datepicker" id="completion_date" value="{{$service->completion_date}}" name="completion_date" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">Customer's Request/Service to Offer:</label>
                                <textarea class="form-control richtext" id="description" name="description" rows="3">{{$service->description}}</textarea>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="status">Status:</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="{{$service->status}}" selected>{{$service->status}}</option>
                                   <option value="Quotation">Quotation</option>
                                   <option value="Inspection">Inspection</option>
                                   <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount">Total Amount:</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{$service->amount}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="amountpaid">Amount Paid:</label>
                                <input type="number" class="form-control" id="amountpaid" name="amountpaid" value="{{$service->amountpaid}}">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Submit Form</label> <br>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-6">
                        <h3>Select Spare-parts and Quantities</h3>
                        <div class="product-select">
                            @if (isset($service->parts) && $service->status!="Quotation")

                                <h3>You have already added the parts</h3>
                                <ul>
                                    @foreach ($service->parts as $part)
                                        <li>{{$part->sparepart->spareparts}} QTY: {{$part->quantity}}</li>
                                    @endforeach
                                </ul>

                                <h3>Add more to Parts list</h3>
                                <div id="product-select2" class="row">
                                    <div class="col-md-8">
                                        <select class="select2 form-control" name="sparepart[]">
                                            <option value="0" selected>Select a Sparepart</option>
                                            @foreach ($spareparts as $spare)
                                                <option value="{{$spare->id}}">{{$spare->spareparts}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="quantity form-control" name="quantity[]" value="1" min="1">
                                    </div>
                                </div>
                            @else
                                @foreach ($service->parts as $part)

                                    <div id="product-select2" class="row">
                                        <div class="col-md-8">

                                            <select class="select2 form-control" name="sparepart[]">
                                                    <option value="{{$part->part_id}}" selected>{{$part->sparepart->spareparts}}</option>
                                                    @foreach ($spareparts as $spare)
                                                        <option value="{{$spare->id}}">{{$spare->spareparts}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="quantity form-control" name="quantity[]" value="{{$part->quantity}}" min="1">
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            @endif


                        </div>

                        <a href="#" class="add-product btn btn-primary">Add Another Part</a>

                        <div class="row">
                            <div class="form-group" style="text-align: center">
                                <label for="takenparts">Have this Parts been taken from the stock?</label>
                                <input type="checkbox" name="takenparts" id="takenparts" value="Yes" @if ($service->status!="Quotation") checked @endif >
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
