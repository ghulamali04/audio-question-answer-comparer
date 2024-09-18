<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Phrase extends Controller
{
    //view
    function index()
    {
      $phrases = DB::table('phrase')->where('is_deleted',0)->paginate(10);
      return view("admin/phrase",["phrases"=>$phrases]);
    }
    //save
    function save(Request $req)
    {
      $text_english = $req->post('text_english');
      $text_spanish = $req->post('text_spanish');
      $file_spanish = $req->file('file1');
      $fileName_spanish = $req->post('fileName1');
      $file_english = $req->file('file2');
      $fileName_english = $req->post('fileName2');
      
      if($text_english != '' && $text_spanish != '')
      {
        $file_spanish->move(public_path().'/adminRecordings/', $fileName_spanish);
        $file_english->move(public_path().'/adminRecordings/', $fileName_english);
        DB::table('phrase')->insert([
          'text_english' => $text_english,
          'text_spanish' => $text_spanish,
          'audio_spanish' => $fileName_spanish,
          'audio_english' => $fileName_english
        ]);
      }
      return response()->json();
    }
    //handle edit request
    function edit(Request $req)
    {
      $id = $req->post('id');
      $text_english = $req->post('text_english');
      $text_spanish = $req->post('text_spanish');
      $audio_spanish = $req->file('blobEdit1');
      $audio_fileName1 = $req->post('fileNameEdit1');
      $audio_english = $req->file('blobEdit2');
      $audio_fileName2 = $req->post('fileNameEdit2');
      $status = 'error';
      $current_date = date('Y-m-d');
      $getOldPhrase = DB::table('phrase')->where('is_deleted',0)->where('id',$id)->first();
      if($audio_spanish && $audio_fileName1 != '')
      {
        unlink(public_path('/adminRecordings'). DIRECTORY_SEPARATOR .$getOldPhrase->audio_spanish);
        //move audio file to public/adminRecordings
        $audio_spanish->move(public_path().'/adminRecordings/', $audio_fileName1);
        DB::update("UPDATE `phrase` SET `audio_spanish`='$audio_fileName1',  `updated_at`='$current_date'
           WHERE `id`='$id'");
        $status = 'success';
      }
      if($audio_english && $audio_fileName2 != '')
      {
        unlink(public_path('/adminRecordings'). DIRECTORY_SEPARATOR .$getOldPhrase->audio_english);
        //move audio file to public/adminRecordings
        $audio_english->move(public_path().'/adminRecordings/', $audio_fileName2);
        DB::update("UPDATE `phrase` SET `audio_english`='$audio_fileName2',  `updated_at`='$current_date'
           WHERE `id`='$id'");
        $status = 'success';
      }
      if($text_english != '' && $text_spanish != '')
      {
        DB::update("UPDATE `phrase` SET `text_english`='$text_english',
          `text_spanish`='$text_spanish', `updated_at`='$current_date' WHERE `id`='$id'");
        $status = 'success';
      }
      return response()->json($status);
    }
    //handle delete request
    function delete(Request $req)
    {
      $id = $req->post('id');
      $status = '';
      /**$check = DB::table('assignment as A')->join('assignment_questionares as AQ','A.id','=','AQ.assignment_id')
      ->where('A.is_deleted',0)->where('A.status','ACTIVE')->where('AQ.question_id',$id)
      ->where('A.typeof','PH')->first();
      if($check && $check->status == 'ACTIVE'){
        $status = 'error';
      }**/
        $getOldPhrase = DB::table('phrase')->where('is_deleted',0)->where('id',$id)->first();
        if(unlink(public_path('/adminRecordings'). DIRECTORY_SEPARATOR .$getOldPhrase->audio_english) && unlink(public_path('/adminRecordings'). DIRECTORY_SEPARATOR .$getOldPhrase->audio_spanish))
        {
          $current_date = date('Y-m-d');
          DB::update("UPDATE `phrase` SET `is_deleted`=1, `deleted_at`='$current_date' WHERE `id`='$id'");
            $status = 'success';
        }
        else{
          $status = 'error';
        }
      return response()->json($status);
    }
}
