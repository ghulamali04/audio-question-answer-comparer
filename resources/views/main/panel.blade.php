<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Dashboard - Learn Spanish App</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="{{asset('public/main/css/bootstrap.css')}}" />
  <link rel="stylesheet" href="{{asset('public/main/css/style.css')}}" />
  <link rel="stylesheet" href="{{asset('public/main/css/media.css')}}"/>
</head>

<body class="bg-light">
  <img src="{{asset('public/ajax-loader.gif')}}" class="d-none gifimg" height="100" width="" style="position: fixed;left: 47%;top:40%;z-index: 1000">

  @if(isset(Auth::user()->email))

  <div class="row mx-0">
    <div class="col-lg-12 px-0">
        <div class="container-fluid px-0">
          <nav class="navbar navbar-expand-lg  pt-2" style="background: linear-gradient(to right,#040961,#040961,#040961,#040961,#040961);">
            <a class="navbar-brand navbar-end" href="{{url('/')}}/user/panel"><strong class="text-white">Learn English</strong></a>
            <button class="navbar-toggler bg-white" style="outline: none; color:#040961;" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fas fa-bars text-1"></i>
            </button>
            <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
              <ul class="navbar-nav">

                <li class="nav-item dropdown">
                  <a class="nav-link text-white" id="navbarDropdownP" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#!">Phrases</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownP" style="background-color: #040961;">
                    <a class="dropdown-item" href="{{url('/user/panel/phrase')}}" style="background-color: #040961; color:#F8F9FA;">Attempt Assignment</a>
                    <a class="dropdown-item" href="{{url('')}}/user/panel/phrase/view/{{Auth::user()->id}}" style="background-color: #040961; color:#F8F9FA;">View Attempts</a>
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link text-white" id="navbarDropdownV" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#!">Vocabulary</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownV" style="background-color: #040961;">
                    <a class="dropdown-item" href="{{url('/user/panel/vocabulary')}}" style="background-color: #040961; color:#F8F9FA;">Attempt Assignment</a>
                    <a class="dropdown-item" href="{{url('')}}/user/panel/vocabulary/view/{{Auth::user()->id}}" style="background-color: #040961; color:#F8F9FA;">View Attempts</a>
                  </div>
                </li>
                <li class="nav-item dropdown std-collapse-li" style="list-style-type: none;" >
                  <a class="nav-link  text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> {{Auth::user()->first_name}}&nbsp;{{Auth::user()->last_name}}</a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background-color: #040961;">

                    <a class="dropdown-item" href="{{url('/user/logout')}}" style="background-color: #040961; color:#F8F9FA;">Logout</a>
                  </div>
                </li>

              </ul>
            </div>
            <ul class="navbar-nav std-nav">
              <li class="nav-item dropdown " style="list-style-type: none;">
                <a class="nav-link  text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> {{Auth::user()->first_name}}&nbsp;{{Auth::user()->last_name}}</a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background-color: #040961;">
                  <a class="dropdown-item" href="{{url('/user/logout')}}" style="background-color: #040961; color:#F8F9FA;">Logout</a>
                </div>
              </li>
            </ul>
          </nav>
        </div>
    </div>
  </div>

  @if(Auth::user()->role == 'USR' && Auth::user()->is_deleted == 0)
  @if(Auth::user()->subscription == 'CONTINUED')
    @if(Auth::user()->status == 'ACTIVE')
      @yield('content')
    @else
    <div class="container py-1 mt-5 ">
        <div class="d-flex justify-content-center">
        <h2 class="text-center">
          It seems your account had been deactivated or deleted by site admin!
        </h2>
        </div>
    </div>
    @endif
  @else
  <div class="container py-1 mt-5 ">
      <div class="d-flex justify-content-center">
      <h2 class="text-center">
        It seems your subscription is expired or you have not paid yet.
      </h2>
      <h2>
        Continue where you left.<a href="{{url('')}}/user/signup/complete-subscripton/{{Auth::user()->email}}">Pay</a>
      </h2>
      </div>
  </div>
  @endif
  @else
  <div class="container py-1 mt-5 ">
      <div class="d-flex justify-content-center">
      <h2 class="text-center">
        It seems your account had been deactivated or deleted by site admin!
      </h2>
      </div>
  </div>
  @endif

  <footer class="footer justify-content-center text-center  mt-2 pt-3" style="
  width: 100%;
  border: 1px solid transparent;
    box-shadow: 0px 12px 20px 0px rgb(255 126 95 / 15%);
    background: linear-gradient(to right,#040961,#040961,#040961,#040961,#040961);
  ">
    <p class="text-center text-white" >
      Developed by <a href="https://ahmedolusanya.com/" style="color: white !important;text-decoration: none !important;">OladSynergy</a>
    </p>
  </footer>
@else
<script>window.location="{{url('/user/login')}}"</script>
@endif
</body>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="{{asset('public/admin/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('public/main/js/bootstrap.js')}}"></script>
<script src="{{asset('public/main/js/app.js')}}"></script>
@yield('javascript')
</html>
