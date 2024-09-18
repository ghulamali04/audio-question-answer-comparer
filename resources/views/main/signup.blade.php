@extends('main/layout')
@section('content')
<section class="banner-background" style="min-height: 100vh;">
    <div class="container py-5 mt-5">
      <div class="card bg-white py-0 px-0 mt-1">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 col-xl-6">
                <div class="signup-clipart">
                  <div class="container-fluid ">

                    </div>
                  </div>
              </div>
              <div class="col-lg-6 col-xl-6">
                <div class="container">
                  <h1 class="mb-3 text-1"><strong class="text-bold">Sign Up!</strong></h1>
                  <p>
                    Already registered.<a href="{{url('')}}/user/login" class="text-1">Sign In</a>!
                    </p>
                    <div class="my-2">
                                @if(isset(Auth::user()->email))
     <script>window.location = "{{url('')}}/admin"</script>
    @endif
    @if($message=Session::get('error'))
        <div class="alert alert-danger"><li>{{$message}}</li></div>
    @endif
    @if($message=Session::get('success'))
        <div class="alert alert-success"><li>{{$message}}</li></div>
    @endif
    @if($errors->any())
    @foreach($errors->all() as $er)
        <div class="alert alert-danger"><li>{{$er}}</li></div>
    @endforeach
    @endif
                                </div>
                  <form method="POST" action="{{url('')}}/user/signup">
                    @csrf
                    <div class="row mb-2">
                      <div class="col-lg-6">
                          <label class="form-label" for="StdName">First Name:</label>
                          <input type="text" class="form-control" id="StdName" name="firstName" value="">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="StdName1">Last Name:</label>
                            <input type="text" class="form-control" id="StdName1" name="lastName" value="">
                          </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-12">
                            <label class="form-label" for="StdEmail">Email:</label>
                            <input type="email" class="form-control" id="StdEmail" name="email" value="">
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-lg-12">
                              <label class="form-label" for="StdPassword">Password:</label>
                              <input type="password" class="form-control" id="StdPassword" name="enterPassword" value="">
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-lg-12">
                                <label class="form-label" for="StdConfirmPassword">Confirm Password:</label>
                                <input type="password" class="form-control" id="StdConfirmPassword" name="confirmPassword" value="">
                              </div>
                            </div>
                            <div class="row mb-2">
                              <div class="col-lg-12">
                                  <input class="btn_submit" value="Register" name="register" type="submit"/>
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
