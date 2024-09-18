<html>

<head>
  <title>Recover Passoword</title>
</head>
<body>
  <h3>Confirm Your Email</h3>
  <p>
    Please click on the link given to verify. Ignore this message if you are not the person to request.
  </p>
  <a href="{{url('')}}/user/signup/verify-email/{{$user->email}}/{{$user->remember_token}}">click here</a>
</body>
</html>
