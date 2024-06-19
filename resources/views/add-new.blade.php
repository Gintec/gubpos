@extends('layouts.theme')

@section('content')
                    <h3 class="page-title">Add New Members </h3>

                    <div class="panel">

                        <div class="panel-body">

                            <form method="POST" action="{{ route('addnew') }}">

                                <div class="row">


                                    @csrf
                                    <div class="col-md-4">


                                        <div class="form-group row">
                                            <label for="name" class="control-label sr-only">{{ __('Name') }}</label>


                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus placeholder="Full Name">

                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="dob" class="control-label sr-only">Date of Birth</label>
                                                    <input id="dob" name="dob" type="text" class="form-control datepicker" placeholder="Date of Birth">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="gender"  class="control-label sr-only">Gender</label>
                                                <select class="form-control" name="gender" id="gender">
                                                    <option value="" selected>Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label for="phone_number" class="control-label sr-only">{{ __('Phone Number') }}</label>


                                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}"  autocomplete="phone_number" autofocus placeholder="Phone Number">

                                                @error('phone_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>


                                        <div class="form-group row">
                                            <label for="about"  class="control-label sr-only">About Member</label>
                                            <textarea name="about" id="about" class="form-control" placeholder="About Member" rows="4"></textarea>
                                        </div>




                                    </div>
                                    <div class="col-md-3  col-md-offset-1">


                                        <div class="form-group row">
                                            <label for="address" class="control-label sr-only">Address</label>
                                                <input id="address" name="address" type="text" class="form-control" placeholder="Address">
                                        </div>

                                        <div class="form-group row">
                                            <label for="location" class="control-label sr-only">Location</label>
                                                <input id="location" name="location" type="text" class="form-control" placeholder="Location">
                                        </div>

                                        <div class="form-group row">
                                            <label for="salary" class="control-label sr-only">Salary</label>
                                                <input id="salary" name="salary" type="text" class="form-control" placeholder="Salary">
                                        </div>

                                        <div class="form-group row">
                                            <label for="category"  class="control-label sr-only">Category</label>
                                            <select class="form-control" name="category" id="category">
                                                <option value="Category" selected>Category</option>
                                                <option value="Customer">Customer</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Distributor">Distributor</option>
                                                <option value="Contractor">Contractor</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-md-offset-1">

                                        <div class="form-group row">
                                            <label for="status"  class="control-label sr-only">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="Status" selected>Status</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Contractor">Contractor</option>
                                                <option value="Customer">Customer</option>
                                                <option value="New Customer">New Customer</option>
                                                <option value="Lost Customer">Lost Customer</option>
                                                <option value="InActive">InActive</option>
                                            </select>
                                        </div>

                                        <div class="form-group row">
                                            <label for="email" class="control-label sr-only">{{ __('E-Mail Address') }}</label>


                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" placeholder="E-mail">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>

                                        <div class="form-group row">
                                            <label for="password" class="control-label sr-only">{{ __('Password') }}</label>


                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>

                                        <div class="form-group row">
                                            <label for="password-confirm" class="control-label sr-only">{{ __('Confirm Password') }}</label>


                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">

                                        </div>

                                        <div class="form-group row">
                                            <label for="role"  class="control-label sr-only">Role</label>
                                            <select class="form-control" name="role" id="role">
                                                <option value="Marketers" selected>Marketers</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Distributor">Distributor</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Contractor">Contractor</option>
                                                @if ($settings->mode=="Maintenance")
                                                <option value="Super">Super</option>
                                                @endif
                                            </select>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group row mb-0">

                                        <button type="submit" class="btn btn-primary pull-right">
                                            {{ __('Add New Member') }}
                                        </button>

                                </div>
                            </form>


                        </div>

                    </div>


@endsection
