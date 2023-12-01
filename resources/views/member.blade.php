@extends('layouts.theme')

@section('content')


    <h3 class="page-title">{{$member->name}} | <small style="color: green">{{$member->status}}</small></h3>
    <div class="row">
            <div class="panel">
                <div class="panel-heading">

                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#task">Add New Task</a>
                        <a href="#" class="btn btn-success pull-right" data-toggle="modal" data-target="#followup">New Followup Activity</a>

                        <hr>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-5 col-md-offset-1">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item active">Basic Info</b></li>
                                <li class="list-group-item">Gender: <b>{{$member->gender}}</b></li>
                                <li class="list-group-item">Date of Birth: <b>{{$member->dob}}</b></li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item active">Contact Persons</b></li>

                                @php
                                    $invited_by = $member->invited_by;
                                    $assigned_to = $member->assigned_to;
                                    $phone_number = '';
                                    $phone_number2 = '';

                                    if($member->invited_by!=""){
                                        $invited_by = $users->where('id',$member->invited_by)->first()->name;
                                        $phone_number = $users->where('id',$member->invited_by)->first()->phone_number;
                                    }


                                    if($member->assigned_to!=""){
                                        $assigned_to = $users->where('id',$member->assigned_to)->first()->name;
                                        $phone_number2 = $users->where('id',$member->invited_by)->first()->phone_number;
                                    }
                                @endphp
                                <li class="list-group-item">Invited By: <b>{{$invited_by}} (<a href="tel:{{$phone_number}}">{{$phone_number}}</a>)</b></li>
                                <li class="list-group-item">Assigned To: <b>{{$assigned_to}} (<a href="tel:{{$phone_number2}}">{{$phone_number2}}</a>)</b></li>
                                <li class="list-group-item">Category: <b>{{$member->ministry}}</b></li>
                            </ul>
                        </div>

                    </div>

                    <div class="col-md-10 col-md-offset-1">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item active">Contact Info</b></li>
                            <li class="list-group-item">Phone Number: <b><a href="tel:{{$member->phone_number}}">{{$member->phone_number}}</a></b></li>
                            <li class="list-group-item">E-mail: <b>{{$member->email}}</b></li>
                            <li class="list-group-item">Address: <b>{{$member->address}} \ Location: {{$member->location}}</b></li>
                        </ul>
                    </div>

                    <div class="col-md-10 col-md-offset-1">

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item active">Assigned Tasks</b></li>
                                @foreach ($tasks as $task)
                                    <li class="list-group-item"><b>{{$task->title}}</b>: {{$task->activities}} <br><i class="lnr lnr-clock"></i>{{$task->date}}</b>: {{$task->status}} {!!$task->status!="Completed"?"<a href='/completetask/".$task->id."' class='label label-success' style='float: right;'><i class='lnr lnr-checkmark-circle'></i> Mark Complete</a>":""!!}</li>
                                @endforeach


                            </ul>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item active">Followup Activities</b></li>
                                @foreach ($followups as $fup)
                                    <li class="list-group-item"><b>{{$fup->title}}</b>({{$fup->date}}):<br> {{$fup->discussion}} <br><span style="color: green"><b>Next Action:</b> {{$fup->nextaction}} </span> <i style="color: orange;">{{$fup->status}}</i> | <small style="color: rgba(63, 14, 6, 0.774);">To Do Date:</small> {{$fup->nextactiondate}} </li>
                                @endforeach


                            </ul>
                    </div>
                </div>
            </div>

    </div>


    <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal" id="task">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add New Task for {{$member->name}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">


            <form method="POST" action="{{ route('newtask') }}">
                @csrf
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="assigned_to" value="{{$member->id}}">
                <input type="hidden" name="phone_number" value="{{$member->phone_number}}">
                <input type="hidden" name="status" value="Not Completed">

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control">
                </div>

                <div class="form-group">
                    <label for="date">Date to Deliver</label>
                    <input type="date" name="date" id="date" class="form-control">
                </div>

                <div class="form-group">
                    <label for="category" class="control-label">Category</label>
                    <select class="form-control" name="category" id="category">
                        <option value="Reminder" selected>Reminder</option>
                        <option value="Admin" selected>Admin</option>
                        <option value="Followup">Followup</option>
                        <option value="Others" selected>Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="activities"  class="control-label">Activities</label>
                    <textarea name="activities" id="activities" class="form-control" placeholder="Activities" rows="4"></textarea>
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Set New Task') }}
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

  <div class="modal" id="followup">
        <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Add New Follow-up Activity for {{$member->name}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">


                <form method="POST" action="{{ route('newfollowup') }}">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="member" value="{{$member->id}}">
                    <input type="hidden" name="status" value="Not Done">
                    <input type="hidden" name="phone_number" value="{{$member->phone_number}}">

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="date">Date Done</label>
                        <input type="date" name="date" id="date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="type" class="control-label">Type</label>
                        <select class="form-control" name="type" id="type">
                            <option value="Call" selected>Call</option>
                            <option value="Visitation" selected>Visitation</option>
                            <option value="Invitation" selected>Invitation</option>
                            <option value="Others" selected>Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="discussion"  class="control-label">Discussion with Customer</label>
                        <textarea name="discussion" id="discussion" class="form-control" placeholder="discussion" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="nextaction">Next Recommended Action</label>
                        <input type="nextaction" name="nextaction" id="nextaction" class="form-control">
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="assigned_to"  class="control-label sr-only">Assigned To</label>
                            <select class="form-control" name="assigned_to" id="assigned_to">
                                <option value="" selected>Assigned To</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nextactiondate">Next Action Date</label>
                            <input type="date" name="nextactiondate" id="nextactiondate" class="form-control">
                        </div>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save Activity') }}
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
