@extends('admin/layout')
@section('css')
<link href="{{asset('public/admin/dist/css/player.css')}}" rel="stylesheet"/>
@stop
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-12">
        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">REGISTER STUDENTS</h4>
                                <form class="mt-3" method="POST" action="{{url('')}}/admin/students/save">
                                  @csrf
                                  @if(Session::get('success'))
                <div class="alert alert-success"><li>
                  {{Session::get('success')}}
                </li></div>
                @endif
                @if(Session::get('error'))
<div class="alert alert-danger"><li>
  {{Session::get('error')}}
</li></div>
@endif
                @if($errors->any())
                @foreach($errors->all() as $er)
                    <div class="alert alert-danger"><li>{{$er}}</li></div>
                @endforeach
                @endif
                                    <div class="form-row">
                                      <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <input type="text" class="form-control" name="firstName" id="nametextFirst" aria-describedby="nametextFirst" placeholder="First Name">
                                            <small id="nametextFirst" class="form-text text-muted">enter first name of student</small>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <input type="text" class="form-control" name="lastName" id="nametextLast" aria-describedby="nametextLast" placeholder="Last Name">
                                            <small id="nametextLast" class="form-text text-muted">enter last name of student</small>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-row">
                                      <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <input type="password" class="form-control" name="enterPassword" id="enterPassword" aria-describedby="enterPassword" placeholder="Password">
                                            <small id="enterPassword" class="form-text text-muted">enter 8 char | aphanum password</small>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" aria-describedby="confirmPassword" placeholder="Repeat Password">
                                            <small id="confirmPassword" class="form-text text-muted">confirm entered password</small>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="email" class="form-control" name="email" id="email" aria-describedby="email" placeholder="Enter your email">
                                        <small id="email" class="form-text text-muted">Enter active | valid email</small>
                                    </div>
                                    <div class="form-group mb-2">
                                      <input type="submit" value="Save" class="btn btn-info" id="submit">
                                      <input type="reset" value="Reset" class="btn btn-dark" id="reset-form">
                                      <small id="name" class="form-text text-muted"></small>
                                    </div>
                                </form>
                            </div>
                        </div>
      </div>
    </div>

</div>




@stop
