@extends('main/layout')
@section('content')
<section class="banner-background" style="min-height: 100vh;">
    <div class="container py-5 mt-5">
      <div class="card bg-white py-0 px-0 mt-1 my-5">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 col-xl-6">
                <div class="signup-clipart">
                  <div class="container-fluid ">

                    </div>
                  </div>
              </div>
              <div class="col-lg-6 col-xl-6 ">
                <div class="container mb-5">
                  <h1 class="mb-3 text-1"><strong class="text-bold">Sign In!</strong></h1>
                  <p>
                    Not a user.<a href="{{url('')}}/user/signup" class="text-1">Sign Up</a> Now!
                    </p>
                    <div class="my-2">
                                @if(isset(Auth::user()->email))
     <script>window.location = "{{url('')}}/admin"</script>
    @endif
    @if($message=Session::get('error'))
        <div class="alert alert-danger"><li>{{$message}}</li></div>
    @endif
    @if($errors->any())
    @foreach($errors->all() as $er)
        <div class="alert alert-danger"><li>{{$er}}</li></div>
    @endforeach
    @endif
                                </div>
                  <form method="POST" action="{{url('')}}/user/login/save-new-password/{{$password_reset->email}}/{{$password_reset->token}}">
                    @csrf
                    <div class="row mb-2">
                      <div class="col-lg-12">
                          <label class="form-label" for="StdPassword">New Password:</label>
                          <input type="password" class="form-control" id="StdPassword" name="new-password" value="">
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-12">
                            <label class="form-label" for="StdPassword1">Confirm Password:</label>
                            <input type="password" class="form-control" id="StdPassword1" name="confirm-password" value="">
                          </div>
                        </div>
                            <div class="row mb-2">
                              <div class="col-lg-12">
                                  <input class="btn_submit" value="Sign In" type="submit"/>
                                </div>
                              </div>
                    </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>
@stop
