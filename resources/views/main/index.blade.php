@extends('main/layout')
@section('content')
<section class="banner" style="min-height: 100vh;">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-xl-4">
            <div class="banner-text">
              <div class="banner-text-inner">
                <h1 class="text-white"><strong>Learn,</strong></h1>
                <h1 class="text-white"><strong>Master Spanish</strong></h1>
                <br />
                <p class="text-white">
                  The Language Learning is an application that focuses on training students how to speak and understand Spanish.
                </p>
                <a href="{{url('')}}/user/signup" style="text-decoration: none;" class="btn_light">Get Started</a>
                <a href="{{url('')}}/user/login" style="text-decoration: none;" class="btn_light">Sign In</a>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
  <div class="container-fluid banner-text-col  my-2 py-5">
    <div class=" justify-content-center text-center py-5">
      <h1 class="text-1"><strong>Learn & Master</strong></h1>
      <h1 class="text-1"><strong>Spanish</strong></h1>
      <br />
      <p class="text-1">
        The Language Learning is an application that focuses on training students how to speak and understand Spanish.
      </p>
      <a href="{{url('')}}/user/signup" class="btn_submit text-white" style="text-decoration: none;">Get Started</a>
      <a href="{{url('')}}/user/login" class="btn_submit text-white" style="text-decoration: none;">Sign In</a>
    </div>
  </div>
@stop
