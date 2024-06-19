@extends('layouts.theme')

@section('content')

                    <div class="card">
                        <div class="card-header">
                            Add New Staff/Customer
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Please Enter Required Fields</h4>

                            <form method="POST" action="{{ route('addnew') }}">

                                <div class="row">


                                    @csrf
                                    <input type="hidden" name="id" value="{{$user->id}}">

                                    <div class="col-md-4">


                                        <div class="form-group row">
                                            <label for="name" class="control-label ">{{ __('Name') }}</label>


                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}"  autocomplete="name" autofocus placeholder="Full Name">

                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="dob" class="control-label ">Date of Birth</label>
                                                    <input id="dob" name="dob" type="date" class="form-control" value="{{ $user->dob }}" placeholder="Date of Birth">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="gender"  class="control-label ">Gender</label>
                                                <select class="form-control" name="gender" id="gender">
                                                    <option value="{{ $user->gender }}" selected>{{ $user->gender }}</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label for="phone_number" class="control-label ">{{ __('Phone Number') }}</label>


                                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ $user->phone_number }}"  autocomplete="phone_number" autofocus placeholder="Phone Number">

                                                @error('phone_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>


                                        <div class="form-group row">
                                            <label for="about"  class="control-label ">About {{$user->category}}</label>
                                            <textarea name="about" class="form-control" placeholder="About Member" rows="4">{{ $user->about }}</textarea>
                                        </div>




                                    </div>
                                    <div class="col-md-3  col-md-offset-1">


                                        <div class="form-group row">
                                            <label for="address" class="control-label ">Address</label>
                                                <input id="address" name="address" type="text" value="{{ $user->address }}" class="form-control" placeholder="Address">
                                        </div>

                                        <div class="form-group row">
                                            <label for="location" class="control-label ">Location</label>
                                                <input id="location" name="location" type="text" class="form-control" value="{{ $user->location }}" placeholder="Location">
                                        </div>

                                        <div class="form-group row">
                                            <label for="category"  class="control-label ">Category</label>
                                            <select class="form-control" name="category" id="category">
                                                <option value="{{ $user->category }}" selected>{{ $user->category }}</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Customer">Customer</option>
                                            </select>
                                        </div>

                                        @if ($user->category!="Customer")
                                            <div class="form-group row">
                                                <label for="salary" class="control-label ">Salary</label>
                                                <input id="salary" name="salary" type="text" class="form-control" value="{{ $user->salary }}" placeholder="Monthly Salary">
                                            </div>
                                        @endif


                                    </div>

                                    <div class="col-md-3 col-md-offset-1">


                                        <div class="form-group row">
                                            <label for="status"  class="control-label ">{{$user->category}} Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="{{ $user->status }}" selected>{{ $user->status }}</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Customer">Customer</option>
                                                <option value="New Customer">New Customer</option>
                                                <option value="Lost Customer">Lost Customer</option>
                                                <option value="Contractor">Contractor</option>
                                            </select>
                                        </div>

                                        <div class="form-group row">
                                            <label for="email" class="control-label ">{{ __('E-Mail Address') }}</label>


                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}"  autocomplete="email" placeholder="E-mail">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>

                                        <div class="form-group row">
                                            <label for="password" class="control-label ">{{ __('Password') }}</label>
                                                <input type="hidden" name="oldpassword" value="{{ $user->password }}">

                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password"placeholder="Password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>

                                        <div class="form-group row">
                                            <label for="password-confirm" class="control-label ">{{ __('Confirm Password') }}</label>


                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password"  placeholder="Confirm Password">

                                        </div>

                                        <div class="form-group row">
                                            <label for="role"  class="control-label sr-only">Role</label>
                                            <select class="form-control" name="role" id="role">
                                                <option value="{{$user->role}}" selected>{{$user->role}}</option>
                                                <option value="Customer">Customer</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Delivery">Delivery</option>
                                                @if ($settings->mode=="Maintenance")
                                                    <option value="Super">Super</option>
                                                @endif
                                            </select>
                                        </div>


                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4 col-md-offset-4">
                                        @if ($user->id)

                                            <a href="{{url('/delete-member/'.$user->id)}}" class="btn btn-danger pull-left" onclick="return confirm('Are you sure you want to delete {{$user->name}}\'s account?')"><i class="fa fa-remove"></i> Delete Member</a>

                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">

                                        <button type="submit" class="btn btn-primary  pull-right">
                                            <i class="fa fa-check"></i>
                                            {{ __('Update '.$user->category.' Info') }}
                                        </button>

                                    </div>

                                </div>

                            </form>


                        </div>

                    </div>


@endsection
