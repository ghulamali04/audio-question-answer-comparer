<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin extends Controller
{
  //login
  function login(Request $req)
  {
      $this->validate($req, [
          'email' => 'required | email',
          'password' => 'required | alphaNum | min:3'
      ]);
      $user_data = array(
          'email' => $req->input('email'),
          'password' => $req->input('password')
      );
      if (Auth::attempt($user_data)) {
          return redirect('/admin-home');
      } else {
          return back()->with('error', 'Wrong login details');
      }
  }
  //logout
  function logout()
  {
      Auth::logout();
      return redirect('/admin/login');
  }
}
