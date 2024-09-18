<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Learn Spanish</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/regular.min.css" />
  <link rel="stylesheet" href="{{asset('public/main/css/bootstrap.css')}}" />
  <link rel="stylesheet" href="{{asset('public/main/css/style.css')}}" />
</head>

<body>
  <header class="header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-lg-12">
          <nav class="navbar navbar-expand-lg navbar-light navbar-expand navbar-toggleable-xl pt-3">
              <div class="collapse navbar-collapse main-menu-item justify-content-start" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-center">

                </ul>
              </div>
              <a class="navbar-brand navbar-end" href="{{url('/')}}"><strong class="brand-c">Learn English</strong></a>

          </nav>
        </div>
      </div>
    </div>
  </header>
  @yield('content')
  <footer class="footer justify-content-center text-center pt-2 pb-2 mt-1" style="
  height: 35px;
  width: 100%;
  border: 1px solid transparent;
    box-shadow: 0px 12px 20px 0px rgb(255 126 95 / 15%);
    background: linear-gradient(to right,#040961,#040961,#040961,#040961,#040961);
  ">
    <p class="text-center text-white" >
      Developed by <a href="" style="color: white !important;text-decoration: none !important;"></a>
    </p>
  </footer>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="{{asset('public/main/js/bootstrap.js')}}"></script>
<script src="{{asset('public/main/js/app.js')}}"></script>
@yield('javascript')
</html>
