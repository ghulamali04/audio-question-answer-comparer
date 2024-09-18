<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Vocabulary extends Controller
{
  //view
  function index()
  {
    $vocabulary = DB::table('vocabulary')->where('is_deleted',0)
    ->orderBy('id','DESC')->paginate(10);
    return view("admin/vocabulary",["vocabulary"=>$vocabulary]);
  }
  //save
  function save(Request $req)
  {
    $text_spanish = $req->post('text_spanish');
    $text_english = $req->post('text_english');
    $audio_english = $req->file('file');
    $audio_fileName = $req->post('fileName');
    $image = $req->file('image');
    if($audio_english != '' && $text_spanish != '' && $audio_fileName != '' && $image != '')
    {
      //move audio file to public/adminRecordings
      $audio_english->move(public_path().'/adminRecordings/', $audio_fileName);
      //move image to public/images
      $image_file =  'DOC' . time() . rand(0, 1000) . '.' . $image->getClientOriginalExtension();
      $image->move(public_path().'/images/', $image_file);
      DB::table('vocabulary')->insert([
        'text_spanish' => $text_spanish,
        'text_english' => $text_english,
        'audio_english' => $audio_fileName,
        'image' => $image_file
      ]);
    }
    return response()->json('success');
  }
  //update vocabulary
  function edit(Request $req)
  {
    $id = $req->post('id');
    $text_english = $req->post('text_english');
    $text_spanish = $req->post('text_spanish');
    $audio_english = $req->file('blobEdit');
    $audio_fileName = $req->post('fileNameEdit');
    $image = $req->file('image');
    $status = 'error';
    $current_date = date('Y-m-d');
    $getOldVocabulary = DB::table('vocabulary')->where('is_deleted',0)->where('id',$id)->first();
    if($image)
    {
      unlink(public_path('/images'). DIRECTORY_SEPARATOR .$getOldVocabulary->image);
      $image_file =  'DOC' . time() . rand(0, 1000) . '.' . $image->getClientOriginalExtension();
      $image->move(public_path().'/images/', $image_file);
      DB::update("UPDATE `vocabulary` SET `image`='$image_file', `updated_at`='$current_date'
         WHERE `id`='$id'");
      $status = 'success';
    }
    if($audio_english && $audio_fileName != '')
    {
      unlink(public_path('/adminRecordings'). DIRECTORY_SEPARATOR .$getOldVocabulary->audio_english);
      //move audio file to public/adminRecordings
      $audio_english->move(public_path().'/adminRecordings/', $audio_fileName);
      DB::update("UPDATE `vocabulary` SET `audio_english`='$audio_fileName',  `updated_at`='$current_date'
         WHERE `id`='$id'");
      $status = 'success';
    }
    if($text_english != '' && $text_spanish != '')
    {
      DB::update("UPDATE `vocabulary` SET `text_english`='$text_english',
        `text_spanish`='$text_spanish', `updated_at`='$current_date' WHERE `id`='$id'");
      $status = 'success';
    }
    return response()->json($status);
  }
  //delete vocabulary
  function delete(Request $req)
  {
    $id = $req->post('id');
    $status = '';
    /**$check = DB::table('assignment as A')->join('assignment_questionares as AQ','A.id','=','AQ.assignment_id')
    ->where('A.is_deleted',0)->where('A.status','ACTIVE')->where('AQ.question_id',$id)
    ->where('A.typeof','VOC')->first();
    if($check && $check->status == 'ACTIVE'){
      $status = 'error';
    }*/
    $getOldVocabulary = DB::table('vocabulary')->where('is_deleted',0)->where('id',$id)->first();
    if(unlink(public_path('/adminRecordings'). DIRECTORY_SEPARATOR .$getOldVocabulary->audio_english) && unlink(public_path('/images'). DIRECTORY_SEPARATOR .$getOldVocabulary->image))
    {
      $current_date = date('Y-m-d');
      DB::update("UPDATE `vocabulary` SET `is_deleted`=1, `deleted_at`='$current_date' WHERE `id`='$id'");
        $status = 'success';
    }else{
      $status = 'error';
    }

    return response()->json($status);
  }
}
