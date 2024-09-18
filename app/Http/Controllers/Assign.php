<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Assign extends Controller
{
  //view phrase assignmets
  function viewPhraseAssign($user)
  {
    try{
      $phrases = DB::table('phrase')->where('is_deleted',0)->orderBy('id','DESC')->get();
      $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','PH')->where('user_id',$user)->first();
      $latest_attempt = $total_attempts->attempt;
      $assignment_id = $total_attempts->id;
      $phrases_assigned = DB::select("SELECT P.text_english as text_english,
        P.text_spanish as text_spanish FROM assignment as A JOIN assignment_questionares as AQ JOIN phrase
        as P WHERE A.id = AQ.assignment_id AND P.id = AQ.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND A.user_id = '$user'");
      $assignments = DB::select("SELECT A.attempt as attempt,P.text_english as text_english,
        P.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no, AR.status as status FROM
        assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN phrase
        as P WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
        AND P.id = AQ.question_id AND P.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        AR.is_deleted = 0 AND P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND
        A.user_id = '$user' AND AR.attempt_no = '$latest_attempt' ORDER BY AR.id DESC");
        $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
        ->where('attempt_no',$latest_attempt)->count();
        $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
        ->where('attempt_no',$latest_attempt)->count();
        $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
        ->where('attempt_no',$latest_attempt)->count();
        return view("admin/phrase_assign",[
          "assignments"=>$assignments,
          "phrases"=>$phrases,
          "phrases_assigned"=>$phrases_assigned,
          "total_attempts"=>$total_attempts,
          "latest_attempt"=>$latest_attempt,
          "user"=>$user,
          "red"=>$red,
          "green"=>$green,
          "orange"=>$orange
        ]);
    }
    catch(\Exception $e){
      $phrases = DB::table('phrase')->where('is_deleted',0)->orderBy('id','DESC')->get();
      $phrases_assigned = DB::select("SELECT P.text_english as text_english,
        P.text_spanish as text_spanish FROM assignment as A JOIN assignment_questionares as AQ JOIN phrase
        as P WHERE A.id = AQ.assignment_id AND P.id = AQ.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND A.user_id = '$user'");
      return view("admin/phrase_assign",[
        "assignments"=>0,
        "phrases_assigned"=>$phrases_assigned,
        "phrases"=>$phrases,
        "total_attempts"=>0,
        "latest_attempt"=>0,
        "user"=>$user,
        "red"=>0,
        "green"=>0,
        "orange"=>0
      ]);
    }
  }
  //select phrase attempts
  function viewPhraseAttempts($user,$attempt_no)
  {
    $phrases = DB::table('phrase')->where('is_deleted',0)->orderBy('id','DESC')->get();
    $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','PH')->where('user_id',$user)->first();
    $latest_attempt = $total_attempts->attempt;
    $assignment_id = $total_attempts->id;
    $assignments = DB::select("SELECT A.attempt as attempt,P.text_english as text_english,
      P.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no,AR.status as status FROM
      assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN phrase
       as P WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
       AND P.id = AQ.question_id AND P.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        AR.is_deleted = 0 AND P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND
        A.user_id = '$user' AND AR.attempt_no = '$attempt_no' ORDER BY AR.id DESC");
    $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $phrases_assigned = DB::select("SELECT P.text_english as text_english,
      P.text_spanish as text_spanish FROM assignment as A JOIN assignment_questionares as AQ JOIN phrase
      as P WHERE A.id = AQ.assignment_id AND P.id = AQ.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
      P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND A.user_id = '$user'");
    return view("admin/phrase_assign",[
      "assignments"=>$assignments,
      "phrases"=>$phrases,
      "total_attempts"=>$total_attempts,
      "phrases_assigned"=>$phrases_assigned,
      "latest_attempt"=>$latest_attempt,
      "user"=>$user,
      "red"=>$red,
      "green"=>$green,
      "orange"=>$orange
    ]);
  }
  //phrase assign
  function createPhraseAssign(Request $req)
  {
    $user = $req->post("user");
    $phrases = json_decode($req->post("phrases"));
    if($user != '' && $phrases != '')
    {
      //find previously active assignment and deactivate
      $current_date = date('Y-m-d');
      DB::table('assignment')->where('is_deleted',0)->where('status','ACTIVE')
      ->where('typeof','PH')->where('user_id',$user)
      ->update([
        'status' => 'DISABLED',
        'updated_at' => $current_date
      ]);
      //add new
      $code = md5(microtime());
      $latest_assignment = DB::table('assignment')->insertGetId([
        'user_id' => $user,
        'status' => 'ACTIVE',
        'typeof' => 'PH',
        'code' => $code
      ]);
      if($latest_assignment)
      {
        //insert assignment questionare
        foreach ($phrases as $phrase) {
          DB::table('assignment_questionares')->insert([
            'question_id' => $phrase,
            'assignment_id' => $latest_assignment
          ]);
        }
      }
    }
  }
  //view phrase assignment to all students
  function viewAllPhraseAssign()
  {
      $phrases = DB::table('phrase')->where('is_deleted',0)->orderBy('id','DESC')->get();
      return view("admin/assign_phrase_to_all",[
        "phrases" => $phrases
      ]);
  }
  //phrase assign to all students
  function createAllPhraseAssign(Request $req)
  {
    $phrases = json_decode($req->post("phrases"));
    if($phrases != '')
    {
      //create array of previously active users
      $users = array();
      $get_users = DB::table('users')->where('is_deleted',0)->where('status','ACTIVE')
      ->where('subscription','CONTINUED')->where('role','USR')->get();
      if ($get_users) {
                    for ($i = 0; $i < count($get_users); $i++) {
                        array_push($users, $get_users[$i]->id);
                    }
                }
      $users = array_values(array_unique($users, SORT_REGULAR));
      //find previously active assignment and deactivate
      $current_date = date('Y-m-d');
      if(!empty($users))
      {
        foreach ($users as $u) {
          //disable old assignment
          DB::table('assignment')->where('is_deleted',0)->where('status','ACTIVE')
          ->where('typeof','PH')->where('user_id',$u)
          ->update([
            'user_id' => $u,
            'status' => 'DISABLED',
            'updated_at' => $current_date
          ]);
        }
        foreach ($users as $u) {
          //add new
          $code = md5(microtime());
          $latest_assignment = DB::table('assignment')->insertGetId([
            'user_id' => $u,
            'status' => 'ACTIVE',
            'typeof' => 'PH',
            'mode' => 'MULTIPLE',
            'code' => $code
          ]);
          if($latest_assignment)
          {
            //insert assignment questionare
            foreach ($phrases as $ph) {
              DB::table('assignment_questionares')->insert([
                'question_id' => $ph,
                'assignment_id' => $latest_assignment
              ]);
            }
          }else{
            DB::table('assignment')->where('id',$latest_assignment)->delete();
          }
        }
      }
    }
  }
  //view vocabulary assignmets
  function viewVocabularyAssign($user)
  {
      try{
        $vocabulary = DB::table('vocabulary')->where('is_deleted',0)->orderBy('id','DESC')->get();
        $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','VOC')->where('user_id',$user)->first();
        $latest_attempt = $total_attempts->attempt;
        $assignment_id = $total_attempts->id;
        $vocabulary_assigned = DB::select("SELECT V.text_english as text_english,
          V.text_spanish as text_spanish FROM assignment as A JOIN assignment_questionares as AQ JOIN vocabulary
          as V WHERE A.id = AQ.assignment_id AND V.id = AQ.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
          V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND A.user_id = '$user'");
        $assignments = DB::select("SELECT A.attempt as attempt,V.text_english as text_english,
          V.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no,AR.status as status FROM
          assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN vocabulary
           as V WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
           AND V.id = AQ.question_id AND V.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
            AR.is_deleted = 0 AND V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND
            A.user_id = '$user' AND AR.attempt_no = '$latest_attempt' ORDER BY AR.id DESC");
            $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
            ->where('attempt_no',$latest_attempt)->count();
            $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
            ->where('attempt_no',$latest_attempt)->count();
            $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
            ->where('attempt_no',$latest_attempt)->count();
        return view("admin/assign_vocabulary",[
          "assignments"=>$assignments,
          "vocabulary"=>$vocabulary,
          "vocabulary_assigned"=>$vocabulary_assigned,
          "total_attempts"=>$total_attempts,
          "latest_attempt"=>$latest_attempt,
          "user"=>$user,
          "red"=>$red,
          "green"=>$green,
          "orange"=>$orange
        ]);
      }catch(\Exception $e)
      {

        $vocabulary = DB::table('vocabulary')->where('is_deleted',0)->orderBy('id','DESC')->get();
        $vocabulary_assigned = DB::select("SELECT V.text_english as text_english,
          V.text_spanish as text_spanish,AR.status as status FROM assignment as A JOIN assignment_questionares as AQ JOIN vocabulary
          as V WHERE A.id = AQ.assignment_id AND V.id = AQ.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
          V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND A.user_id = '$user'");
        return view("admin/assign_vocabulary",[
          "assignments"=>0,
          "vocabulary"=>$vocabulary,
          "vocabulary_assigned"=>$vocabulary_assigned,
          "total_attempts"=>0,
          "latest_attempt"=>0,
          "user"=>$user,
          "red"=>0,
          "green"=>0,
          "orange"=>0
        ]);
      }
  }
  //select vocabulary attempts
  function viewVocabularyAttempts($user,$attempt_no)
  {
    $vocabulary = DB::table('vocabulary')->where('is_deleted',0)->orderBy('id','DESC')->get();
    $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','VOC')->where('user_id',$user)->first();
    $latest_attempt = $total_attempts->attempt;
    $assignment_id = $total_attempts->id;
    $vocabulary_assigned = DB::select("SELECT V.text_english as text_english,
      V.text_spanish as text_spanish FROM assignment as A JOIN assignment_questionares as AQ JOIN vocabulary
      as V WHERE A.id = AQ.assignment_id AND V.id = AQ.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
      V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND A.user_id = '$user'");
    $assignments = DB::select("SELECT A.attempt as attempt,V.text_english as text_english,
      V.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no,AR.status as status FROM
      assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN vocabulary
       as V WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
       AND V.id = AQ.question_id AND V.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        AR.is_deleted = 0 AND V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND
        A.user_id = '$user' AND AR.attempt_no = '$attempt_no' ORDER BY AR.id DESC");
    $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    return view("admin/assign_vocabulary",[
      "assignments"=>$assignments,
      "vocabulary"=>$vocabulary,
      "vocabulary_assigned"=>$vocabulary_assigned,
      "total_attempts"=>$total_attempts,
      "latest_attempt"=>$latest_attempt,
      "user"=>$user,
      "red"=>$red,
      "green"=>$green,
      "orange"=>$orange
    ]);
  }
  //phrase assign
  function createVocabularyAssign(Request $req)
  {
    $user = $req->post("user");
    $vocabulary = json_decode($req->post("vocabulary"));
    if($user != '' && $vocabulary != '')
    {
      //find previously active assignment and deactivate
      $current_date = date('Y-m-d');
      DB::table('assignment')->where('is_deleted',0)->where('status','ACTIVE')
      ->where('typeof','VOC')->where('user_id',$user)
      ->update([
        'status' => 'DISABLED',
        'updated_at' => $current_date
      ]);
      //add new
      $code = md5(microtime());
      $latest_assignment = DB::table('assignment')->insertGetId([
        'user_id' => $user,
        'status' => 'ACTIVE',
        'typeof' => 'VOC',
        'code' => $code
      ]);
      if($latest_assignment)
      {
        //insert assignment questionare
        foreach ($vocabulary as $voc) {
          DB::table('assignment_questionares')->insert([
            'question_id' => $voc,
            'assignment_id' => $latest_assignment
          ]);
        }
      }
    }
  }
  //view vocabulary assignment to all students
  function viewAllVocabularyAssign()
  {
      $vocabulary = DB::table('vocabulary')->where('is_deleted',0)->orderBy('id','DESC')->get();

      return view("admin/assign_vocabulary_to_all",[
        "vocabulary" => $vocabulary
      ]);
  }
  //vocabulary assign to all students
  function createAllVocabularyAssign(Request $req)
  {
    $vocabulary = json_decode($req->post("vocabulary"));
    $status = '';
    if($vocabulary != '')
    {
      //create array of previously active users
      $users = array();
      $get_users = DB::table('users')->where('is_deleted',0)->where('status','ACTIVE')
      ->where('subscription','CONTINUED')->where('role','USR')->get();
      if ($get_users) {
                    for ($i = 0; $i < count($get_users); $i++) {
                        array_push($users, $get_users[$i]->id);
                    }
                }
      $users = array_values(array_unique($users, SORT_REGULAR));
      //find previously active assignment and deactivate
      $current_date = date('Y-m-d');
      if(!empty($users))
      {
        foreach ($users as $u) {
          //disable old assignment
          DB::table('assignment')->where('is_deleted',0)->where('status','ACTIVE')
          ->where('typeof','VOC')->where('user_id',$u)
          ->update([
            'user_id' => $u,
            'status' => 'DISABLED',
            'updated_at' => $current_date
          ]);
        }
        foreach($users as $u)
        {
          //add new
          $code = md5(microtime());
          $latest_assignment = DB::table('assignment')->insertGetId([
            'user_id' => $u,
            'status' => 'ACTIVE',
            'typeof' => 'VOC',
            'mode' => 'MULTIPLE',
            'code' => $code
          ]);
          if($latest_assignment)
          {
            //insert assignment questionare
            foreach ($vocabulary as $voc) {
              DB::table('assignment_questionares')->insert([
                'question_id' => $voc,
                'assignment_id' => $latest_assignment
              ]);
            }
          }else{
            DB::table('assignment')->where('id',$latest_assignment)->delete();
          }
        }
      }else{
        $status = 'error';
      }
    }
    return response()->json($status);
  }




/* USER SIDE */
  //view phrase assignmets
  function viewPanelPhrases($user)
  {
    try{
      $phrases = DB::table('phrase')->where('is_deleted',0)->orderBy('id','DESC')->get();
      $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','PH')->where('user_id',$user)->first();
      $latest_attempt = $total_attempts->attempt;
      $assignment_id = $total_attempts->id;
      $assignments = DB::select("SELECT A.attempt as attempt,P.text_english as text_english,
        P.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no, AR.status as status FROM
        assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN phrase
        as P WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
        AND P.id = AQ.question_id AND P.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        AR.is_deleted = 0 AND P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND
        A.user_id = '$user' AND AR.attempt_no = '$latest_attempt' ORDER BY AR.id DESC");
        $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
        ->where('attempt_no',$latest_attempt)->count();
        $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
        ->where('attempt_no',$latest_attempt)->count();
        $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
        ->where('attempt_no',$latest_attempt)->count();
        return view("main/phraseAttempts",[
          "assignments"=>$assignments,
          "phrases"=>$phrases,
          "total_attempts"=>$total_attempts,
          "latest_attempt"=>$latest_attempt,
          "user"=>$user,
          "red"=>$red,
          "green"=>$green,
          "orange"=>$orange
        ]);
    }catch(\Exception $e)
    {
      return view("main/phraseAttempts",[
        "assignments"=>0,
        "phrases"=>0,
        "total_attempts"=>0,
        "latest_attempt"=>0,
        "user"=>$user,
        "red"=>0,
        "green"=>0,
        "orange"=>0
      ]);
    }
  }
  //select phrase attempts
  function viewPanelPhraseAttempts($user,$attempt_no)
  {
    $phrases = DB::table('phrase')->where('is_deleted',0)->orderBy('id','DESC')->get();
    $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','PH')->where('user_id',$user)->first();
    $latest_attempt = $total_attempts->attempt;
    $assignment_id = $total_attempts->id;
    $assignments = DB::select("SELECT A.attempt as attempt,P.text_english as text_english,
      P.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no,AR.status as status FROM
      assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN phrase
       as P WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
       AND P.id = AQ.question_id AND P.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        AR.is_deleted = 0 AND P.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'PH' AND
        A.user_id = '$user' AND AR.attempt_no = '$attempt_no' ORDER BY AR.id DESC");
    $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    return view("main/phraseAttempts",[
      "assignments"=>$assignments,
      "phrases"=>$phrases,
      "total_attempts"=>$total_attempts,
      "latest_attempt"=>$latest_attempt,
      "user"=>$user,
      "red"=>$red,
      "green"=>$green,
      "orange"=>$orange
    ]);
  }
  //view vocabulary assignmets
  function viewPanelVocabulary($user)
  {
      try{
        $vocabulary = DB::table('vocabulary')->where('is_deleted',0)->orderBy('id','DESC')->get();
        $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','VOC')->where('user_id',$user)->first();
        $latest_attempt = $total_attempts->attempt;
        $assignment_id = $total_attempts->id;
        $assignments = DB::select("SELECT A.attempt as attempt,V.text_english as text_english,
          V.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no,AR.status as status FROM
          assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN vocabulary
           as V WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
           AND V.id = AQ.question_id AND V.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
            AR.is_deleted = 0 AND V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND
            A.user_id = '$user' AND AR.attempt_no = '$latest_attempt' ORDER BY AR.id DESC");
            $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
            ->where('attempt_no',$latest_attempt)->count();
            $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
            ->where('attempt_no',$latest_attempt)->count();
            $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
            ->where('attempt_no',$latest_attempt)->count();
        return view("main/vocabularyAttempts",[
          "assignments"=>$assignments,
          "vocabulary"=>$vocabulary,
          "total_attempts"=>$total_attempts,
          "latest_attempt"=>$latest_attempt,
          "user"=>$user,
          "red"=>$red,
          "green"=>$green,
          "orange"=>$orange
        ]);
      }catch(\Exception $e)
      {
        return view("main/vocabularyAttempts",[
          "assignments"=>0,
          "vocabulary"=>0,
          "total_attempts"=>0,
          "latest_attempt"=>0,
          "user"=>$user,
          "red"=>0,
          "green"=>0,
          "orange"=>0
        ]);
      }
  }
  //select vocabulary attempts
  function viewPanelVocabularyAttempts($user,$attempt_no)
  {
    $vocabulary = DB::table('vocabulary')->where('is_deleted',0)->orderBy('id','DESC')->get();
    $total_attempts = DB::table('assignment')->where('status','ACTIVE')->where('typeof','VOC')->where('user_id',$user)->first();
    $latest_attempt = $total_attempts->attempt;
    $assignment_id = $total_attempts->id;
    $assignments = DB::select("SELECT A.attempt as attempt,V.text_english as text_english,
      V.text_spanish as text_spanish, AR.answer as answer, AR.attempt_no as attempt_no,AR.status as status FROM
      assignment as A JOIN assignment_questionares as AQ JOIN academic_record as AR JOIN vocabulary
       as V WHERE A.id = AQ.assignment_id AND A.id = AR.assignment_id AND AQ.question_id = AR.question_id
       AND V.id = AQ.question_id AND V.id = AR.question_id AND A.is_deleted = 0 AND AQ.is_deleted =0 AND
        AR.is_deleted = 0 AND V.is_deleted = 0 AND A.status = 'ACTIVE' AND A.typeof = 'VOC' AND
        A.user_id = '$user' AND AR.attempt_no = '$attempt_no' ORDER BY AR.id DESC");
    $red = DB::table('academic_record')->where('is_deleted',0)->where('status','red')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $green = DB::table('academic_record')->where('is_deleted',0)->where('status','green')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    $orange = DB::table('academic_record')->where('is_deleted',0)->where('status','orange')->where('assignment_id',$assignment_id)
    ->where('attempt_no',$attempt_no)->count();
    return view("main/vocabularyAttempts",[
      "assignments"=>$assignments,
      "vocabulary"=>$vocabulary,
      "total_attempts"=>$total_attempts,
      "latest_attempt"=>$latest_attempt,
      "user"=>$user,
      "red"=>$red,
      "green"=>$green,
      "orange"=>$orange
    ]);
  }
}
