<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class Subscription extends Controller
{
    //signup
    function signup(Request $req)
    {
      $validated = $req->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required | email',
            'enterPassword' => 'required | alphaNum | min:8',
            'confirmPassword' => 'required | alphaNum | min:8 | same:enterPassword'
        ]);
        if($validated)
        {
          $email = $req->input('email');
          $uniqueEmail = DB::table('users')->where('is_deleted',0)->where('role','USR')
          ->where('email',$email)->first();
          if($uniqueEmail)
          {
            $req->session()->flash('error', 'Email address already in use by someone else');
            return redirect()->back();
          }else{
            $first_name = $req->input('firstName');
            $last_name = $req->input('lastName');
            $password = Hash::make($req->input('enterPassword'));
            $remember_token = Str::random(20);
            DB::table('users')->insert([
              'first_name' => $first_name,
              'last_name' => $last_name,
              'password' => $password,
              'remember_token' => $remember_token,
              'paypal' => $email,
              'email' => $email,
              'subscription' => 'EXPIRED'
            ]);
            $user = DB::table('users')->where('email',$email)->where('is_deleted',0)->first();
            Mail::to($user->email)->send(new VerifyEmail($user));
           $req->session()->flash('success','You can verify your email by click on link sent to your email.');
           return redirect()->back();
          }
        }
    }
    //verify email
    function verifyEmail($email,$remember_token)
    {
      $verifyUser = DB::table('users')->where('email',$email)->where('remember_token',$remember_token)->where('is_deleted',0)->first();
      if($verifyUser)
      {
        $current = date('Y-m-d');
        DB::update("UPDATE `users` SET `email_verified_at`='$current' WHERE `id`='$verifyUser->id'");
        $fee = DB::table('fee')->where('id',1)->first();
        return view("main/subscribe",[
          "user" => $verifyUser,
          "fee" => $fee
        ]);
      }
    }
    //confirm payment
    function confirmPayment(Request $req)
    {
      $amount = $req->post('amount');
      $id = $req->post('id');
      $status = '';
      if($amount != '' && $id != '')
      {
        DB::update("UPDATE `users` SET `payment`='$amount', `subscription`='CONTINUED' WHERE `id`='$id'");
        $status = 'success';
      }
      else{
        $status = 'error';
      }
      return response()->json($status);
    }
    //complete subscription if not yet
    function completeSubscription($email)
    {
      $get_verified_user = DB::table('users')->where('email',$email)->where('status','ACTIVE')->where('is_deleted',0)->first();
      $fee = DB::table('fee')->where('id',1)->first();
      return view("main/subscribe",[
        "user" => $get_verified_user,
        "fee" => $fee
      ]);
    }
    //set subscripton fee view
    function setFeeView()
    {

      $fee = DB::table('fee')->first();
      return view("admin/payment",[
        "fee" => $fee
      ]);
    }
    //set subscripton fee
    function setFee(Request $req)
    {
      $validated = $req->validate([
        "newAmount" => 'required | numeric'
      ]);
      if($validated)
      {
        $amount = $req->input("newAmount");
        DB::update("UPDATE `fee` SET `amount`='$amount' WHERE `id`=1");
        $req->session()->flash('success','succesfuly changed.');
        return redirect()->back();
      }
    }
}
