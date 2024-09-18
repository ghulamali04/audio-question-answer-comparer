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
                                <form class="mt-3" method="POST" action="{{url('')}}/admin/students/subscription-fee">
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
                                            <input type="text" class="form-control" name="oldAmount" id="oldAmount" aria-describedby="oldAmount" value="${{$fee->amount}}" readonly>
                                            <small id="oldAmount" class="form-text text-muted">Active Subscription Fee</small>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <input type="text" class="form-control" name="newAmount" id="newAmount" aria-describedby="newAmount" placeholder="enter amount">
                                            <small id="nametextLast" class="form-text text-muted">Add New Subscription Fee</small>
                                        </div>
                                      </div>
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
