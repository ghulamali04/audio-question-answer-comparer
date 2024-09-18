<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Phrase;
use App\Http\Controllers\Vocabulary;
use App\Http\Controllers\Student;
use App\Http\Controllers\Assign;
use App\Http\Controllers\Subscription;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** ADMIN SIDE */
Route::view("/admin-home","admin/index");
//admin login
Route::view("/admin/login","admin/login");
Route::post("/admin/login",[Admin::class, 'login']);
Route::get("/admin/logout", [Admin::class, 'logout']);
//Phrases
Route::get("/admin/phrase",[Phrase::class, 'index']);
Route::post("/admin/phrase",[Phrase::class, 'save']);
Route::post("/admin/phrase/delete",[Phrase::class, 'delete']);
Route::post("/admin/phrase/edit",[Phrase::class, 'edit']);

//vocabulary
Route::get("/admin/vocabulary",[Vocabulary::class, 'index']);
Route::post("/admin/vocabulary",[Vocabulary::class, 'save']);
Route::post("/admin/vocabulary/delete",[Vocabulary::class, 'delete']);
Route::post("/admin/vocabulary/edit",[Vocabulary::class, 'edit']);

//students
Route::get("/admin/students",[Student::class, 'index']);
Route::view("/admin/students/register","admin/student-insert");
Route::post("/admin/students/save",[Student::class, 'save']);
Route::post("/admin/students/edit",[Student::class, 'edit']);
Route::post("/admin/students/delete",[Student::class, 'delete']);
Route::post("/admin/students/toggle",[Student::class, 'toggle']);

//set subscription fee
Route::get("/admin/students/subscription-fee",[Subscription::class, 'setFeeView']);
Route::post("/admin/students/subscription-fee",[Subscription::class, 'setFee']);

//phrase assignment
Route::get("/admin/phrase/assign/{user}",[Assign::class,'viewPhraseAssign']);
Route::post("/admin/phrase/assign",[Assign::class,'createPhraseAssign']);
Route::get("/admin/phrase/all/assign",[Assign::class, 'viewAllPhraseAssign']);
Route::post("/admin/phrase/all/assign",[Assign::class, 'createAllPhraseAssign']);
Route::get("/admin/phrase/assign/view-attempt/{user}/{attempt_no}",[Assign::class, 'viewPhraseAttempts']);

//vocabulary assignment
Route::get("/admin/vocabulary/assign/{user}",[Assign::class,'viewVocabularyAssign']);
Route::post("/admin/vocabulary/assign",[Assign::class,'createVocabularyAssign']);
Route::get('/admin/vocabulary/all/assign', [Assign::class, 'viewAllVocabularyAssign']);
Route::post('/admin/vocabulary/all/assign', [Assign::class, 'createAllVocabularyAssign']);
Route::get("/admin/vocabulary/assign/view-attempt/{user}/{attempt_no}",[Assign::class, 'viewVocabularyAttempts']);

/** USER SIDE */
Route::view("/","main/index");
//user login
Route::view("/user/login","main/login");
Route::post("/user/login",[Student::class, 'login']);
Route::get("/user/logout", [Student::class, 'logout']);

//user signup
Route::view("/user/signup","main/signup");
Route::post("/user/signup",[Subscription::class, 'signup']);
Route::get("/user/signup/verify-email/{email}/{remember_token}",[Subscription::class, 'verifyEmail']);
Route::get("/user/signup/complete-subscripton/{email}",[Subscription::class, 'completeSubscription']);
Route::post("/user/signup/subscription",[Subscription::class, 'confirmPayment']);

//forget password
Route::view("/user/login/forget-password","main/forgetPasswordView");
Route::post("/user/login/forget-password",[Student::class, 'requestPasswordChange']);
Route::get("/user/login/change-password/{email}/{remember_token}",[Student::class, 'changePassword']);
Route::post("/user/login/save-new-password/{email}/{remember_token}",[Student::class, 'savePassword']);

//user panel
Route::view("/user/panel","main/homeView");

//panel phrase
Route::get("/user/panel/phrase",[Student::class, 'phraseView']);
Route::get("/user/panel/phrase/next",[Student::class,'phraseNext']);
Route::post("/user/panel/phrase/answer-save",[Student::class, 'phraseAnswerSave']);

//panel vocabulary
Route::get("/user/panel/vocabulary",[Student::class, 'vocabularyView']);
Route::get("/user/panel/vocabulary/next",[Student::class,'vocabularyNext']);
Route::post("/user/panel/vocabulary/answer-save",[Student::class, 'vocabularyAnswerSave']);

//view previous phrase attempts
Route::get("/user/panel/phrase/view/{user}",[Assign::class,'viewPanelPhrases']);
Route::get("/user/panel/phrase/view/attempt/{user}/{attempt_no}",[Assign::class, 'viewPanelPhraseAttempts']);

//view previous phrase attempts
Route::get("/user/panel/vocabulary/view/{user}",[Assign::class,'viewPanelVocabulary']);
Route::get("/user/panel/vocabulary/view/attempt/{user}/{attempt_no}",[Assign::class, 'viewPanelVocabularyAttempts']);
