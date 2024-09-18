<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetPassword;

class Student extends Controller
{
    /** ADMIN SIDE */
    //index
    function index()
    {
      $students = DB::table('users')->where('is_deleted',0)->where('role','USR')
      ->paginate(10);
      return view("admin/student-view",["students"=>$students]);
    }
    //toggle
    function toggle(Request $req)
    {
      $id = $req->post('id');
      $user = DB::table('users')->where('is_deleted',0)->where('id',$id)->first();
      if($user->subscription == 'EXPIRED')
      {
        DB::update("UPDATE `users` SET `status`='ACTIVE',`subscription`='CONTINUED' WHERE `id`='$id'");
      }
      if($user->subscription == 'CONTINUED')
      {
        DB::update("UPDATE `users` SET `status`='DISABLED',`subscription`='EXPIRED' WHERE `id`='$id'");
      }
      return response()->json();
    }
    //save
    function save(Request $req)
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
              'email_verified_at' => date('Y-m-d')
            ]);
            $req->session()->flash('success','Successfuly registered.');
            return redirect()->back();
          }
        }
    }
    //edit
    function edit(Request $req)
    {
      $first_name = $req->post('first_name');
      $last_name = $req->post('last_name');
      $email = $req->post('email');
      $id = $req->post('id');
      $status = '';
      if($first_name != '' && $last_name != '' && $email != '')
      {
        $getStudent = DB::table('users')->where('is_deleted',0)->where('id',$id)->first();
        if($getStudent->email != $email)
        {
          $uniqueEmail = DB::table('users')->where('is_deleted',0)->where('role','USR')
          ->where('email',$email)->first();
          if($uniqueEmail)
          {
            $status = 'error-email';
          }else{
            $current_date = date('Y-m-d');
            DB::update("UPDATE `users` SET `email`='$email',`first_name`='$first_name',`last_name`='$last_name',
               `updated_at`='$current_date' WHERE `id`='$id'");
            $status = 'success';
          }
        }else{
          $current_date = date('Y-m-d');
          DB::update("UPDATE `users` SET `first_name`='$first_name',`last_name`='$last_name',
             `updated_at`='$current_date' WHERE `id`='$id'");
          $status = 'success';
        }
      }else{
        $status = 'error';
      }
      return response()->json($status);
    }
    //delete
    function delete(Request $req)
    {
        $status = '';
        $id = $req->post('id');
        $assignment = DB::table('assignment')->where('user_id',$id)->first();
        try{
          DB::table('assignment_questionares')->where('assignment_id',$assignment->id)->delete();
          DB::table('academic_record')->where('assignment_id',$assignment->id)->delete();
          DB::table('assignment')->where('user_id',$id)->where('id',$assignment->id)->delete();
          DB::table('users')->where('id',$id)->delete();
          $status = 'success';
        }
        catch(Exception $e){
          $status = 'error';
        }
      return response()->json($status);
    }
    /** USER SIDE */
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
            return redirect('/user/panel');
        } else {
            return redirect('/user/login')->with('error', 'Wrong login details');
        }
    }
    //request password change
    function requestPasswordChange(Request $req)
    {
      $validated = $req->validate([
        'email' => 'required | email'
      ]);
      if($validated)
      {
        $email = $req->input('email');
        $user = DB::table('users')->where('is_deleted',0)->where('status','ACTIVE')
        ->where('email',$email)->first();
        if($user)
        {
            $password_reset = DB::table('password_resets')->insertGetId([
              'token' => Str::random(10),
              'email' => $user->email
            ]);
            $get_user_token = DB::table('password_resets')->where('id',$password_reset)->first();
            Mail::to($user->email)->send(new ForgetPassword($get_user_token));
           $req->session()->flash('success','You can change your password by click on line sent to your email.');
           return redirect()->back();
        }else{
          return redirect('/user/login/')->with('error','Email does not exists');
        }
      }
    }
    //change password
    function changePassword($email,$remember_token)
    {
      $verifiedUser = DB::table('password_resets')->where('token', $remember_token)->where('email',$email)->first();
        if ($verifiedUser) {
          //DB::table('password_resets')->find($verifiedUser->id)->delete();
          return view("main/changePassword",[
            'password_reset' => $verifiedUser,
          ]);
        }else{
          return redirect('/user/login');
        }
    }
    //handle change password post request
    function savePassword(Request $req,$email,$remember_token)
    {
      $verifiedUser = DB::table('password_resets')->where('token', $remember_token)->where('email',$email)->first();
        if ($verifiedUser) {
          $validated = $req->validate([
            'new-password' => 'required | alphaNum | min:8',
            'confirm-password' => 'required | alphaNum | min:8 | same:new-password'
          ]);
          if($validated){
            $current_date = date('Y-m-d');
            $password = Hash::make($req->input('new-password'));
            DB::update("UPDATE `users` SET `password`='$password',
               `updated_at`='$current_date' WHERE `email`='$verifiedUser->email' AND `is_deleted`=0");
               DB::table('password_resets')->where('id',$verifiedUser->id)->delete();
               return redirect('/user/login');
          }
        }else{
          $req->session()->flash('error', 'link expired');
          return redirect()->back();
        }
    }
    //logout
    function logout()
    {
        Auth::logout();
        return redirect('/user/login');
    }
    //phrase attempt
    function phraseView()
    {
      $user = Auth::user()->id;
      $phrases = DB::table('assignment as A')->join('assignment_questionares as AQ','A.id','=','AQ.assignment_id')
      ->join('phrase as P','P.id','=','AQ.question_id')->where('P.is_deleted',0)->where('A.user_id',$user)
      ->where('A.status','ACTIVE')->where('A.is_deleted',0)->where('AQ.is_deleted',0)->where('A.typeof','PH')
      ->select('P.id as id','P.text_english as text_english')->inRandomOrder()->limit(10)->get();

      //make array of ids
      $phraseIdList = array();
      if ($phrases) {
            for ($i = 0; $i < count($phrases); $i++) {
                array_push($phraseIdList, $phrases[$i]->id);
            }
        }
      //pass first question
      $phrase1 = array();
      if(!empty($phraseIdList))
      {
          $phrase1 = DB::table('phrase')->where('id',$phraseIdList[0])->first();
      }
      //get assignment
      $assignment = DB::table('assignment')->where('user_id',$user)->where('is_deleted',0)
      ->where('typeof','PH')->where('status','ACTIVE')->first();

      return view("main/phraseView",[
        "phrases" => $phrases,
        "phrase1" => $phrase1,
        "phraseIdList" => json_encode($phraseIdList),
        "assignment" => $assignment
      ]);
    }
    //phrase next
    function phraseNext(Request $req)
    {
      $id = $req->get('id');
      $nextPhrase = DB::table('phrase')->where('id',$id)->first();
      return response()->json($nextPhrase);
    }
    //save answers of phrase panel
    function phraseAnswerSave(Request $req)
    {
      $answers = json_decode($req->post('answers'));
      $questions_id = json_decode($req->post('questions_id'));
      $assignment = $req->post('assignment');
      $redList = json_decode($req->post('redList'));
      $greenList = json_decode($req->post('greenList'));
      $orangeList =json_decode($req->post('orangeList'));
      if($answers != '' && $questions_id != '')
      {
        $get_attempt = DB::table('assignment')->where('is_deleted',0)->where('id',$assignment)->first();
        if($get_attempt){
          //increment attempts
          $latest_attempt = ($get_attempt->attempt)+1;
          DB::table('assignment')->where('id',$assignment)->update([
            "attempt" => $latest_attempt
          ]);
          $status = '';
          for($i=0;$i<count($answers);$i++)
          {
            for($j=0;$j<count($questions_id);$j++)
            {
              //check answer status
              if(in_array($questions_id[$j],$redList,True))
              {
                $status = 'red';
              }
              if(in_array($questions_id[$j],$greenList,True))
              {
                $status = 'green';
              }
              if(in_array($questions_id[$j],$orangeList,True))
              {
                $status = 'orange';
              }
              if($i == $j){
                DB::table('academic_record')->insert([
                  'question_id' => $questions_id[$j],
                  'answer' => $answers[$i],
                  'status' => $status,
                  'assignment_id' => $assignment,
                  'attempt_no' => $latest_attempt,
                ]);
              }
            }
            $status = '';
          }
        }
      }
      return response()->json();
    }
    //vocabulary attempt
    function vocabularyView()
    {
      $user = Auth::user()->id;
      $vocabulary = DB::table('assignment as A')->join('assignment_questionares as AQ','A.id','=','AQ.assignment_id')
      ->join('vocabulary as V','V.id','=','AQ.question_id')->where('V.is_deleted',0)->where('A.user_id',$user)
      ->where('A.status','ACTIVE')->where('A.is_deleted',0)->where('AQ.is_deleted',0)->where('A.typeof','VOC')
      ->select('V.id as id','V.text_english as text_english', 'V.text_spanish as text_spanish',
      'V.audio_english as audio_english')->inRandomOrder()->get();

      //make array of ids
      $vocabularyIdList = array();
      if ($vocabulary) {
            for ($i = 0; $i < count($vocabulary); $i++) {
                array_push($vocabularyIdList, $vocabulary[$i]->id);
            }
        }
      //pass first question
      $vocabulary1 = array();
      if(!empty($vocabularyIdList))
      {
          $vocabulary1 = DB::table('vocabulary')->where('id',$vocabularyIdList[0])->first();
      }
      //get assignment
      $assignment = DB::table('assignment')->where('user_id',$user)->where('is_deleted',0)
      ->where('typeof','VOC')->where('status','ACTIVE')->first();

      return view("main/vocabularyView",[
        "vocabulary" => $vocabulary,
        "vocabulary1" => $vocabulary1,
        "vocabularyIdList" => json_encode($vocabularyIdList),
        "assignment" => $assignment
      ]);
    }
    //vocabulary next
    function vocabularyNext(Request $req)
    {
      $id = $req->get('id');
      $nextPhrase = DB::table('vocabulary')->where('id',$id)->first();
      return response()->json($nextPhrase);
    }
    //save answers of vocabulary
    function vocabularyAnswerSave(Request $req)
    {
      $answers = json_decode($req->post('answers'));
      $questions_id = json_decode($req->post('questions_id'));
      $redList = json_decode($req->post('redList'));
      $greenList = json_decode($req->post('greenList'));
      $orangeList = json_decode($req->post('orangeList'));
      $assignment = $req->post('assignment');
      if($answers != '' && $questions_id != '')
      {
        $get_attempt = DB::table('assignment')->where('is_deleted',0)->where('id',$assignment)->first();
        if($get_attempt){
          //increment attempts
          $latest_attempt = ($get_attempt->attempt)+1;
          DB::table('assignment')->where('id',$assignment)->update([
            "attempt" => $latest_attempt
          ]);
          $status = '';
          for($i=0;$i<count($answers);$i++)
          {
            for($j=0;$j<count($questions_id);$j++)
            {
              //check answer status
              if(in_array($questions_id[$j],$redList,True))
              {
                $status = 'red';
              }
              if(in_array($questions_id[$j],$greenList,True))
              {
                $status = 'green';
              }
              if(in_array($questions_id[$j],$orangeList,True))
              {
                $status = 'orange';
              }
              if($i == $j){
                DB::table('academic_record')->insert([
                  'question_id' => $questions_id[$j],
                  'answer' => $answers[$i],
                  'status' => $status,
                  'assignment_id' => $assignment,
                  'attempt_no' => $latest_attempt,
                ]);
              }
            }
          }
        }
      }
      return response()->json();
    }
}
