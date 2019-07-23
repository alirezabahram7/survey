<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('basic.token')->get('/user', function (Request $request) {
    return 'not accessible normally' ;
});

Route::post('login','UserController@login');
Route::middleware('basic.token')->get('/test','PollController@test');
Route::middleware('basic.token')->group(function () {

    //app

    Route::get('/app', 'AppController@index');
    Route::get('/app/{app}', 'AppController@show');

    Route::apiResources([
        'poll' => 'PollController',
        'category' => 'CategoryController',
        'question' => 'QuestionController',
        'option' => 'OptionController',
       '/poll/{poll}/answer' => 'AnswerController'
    ]);

    Route::get('/answer-type', 'AnswerTypeController@index');

    //Answers Post
    Route::post('poll/{poll}/answer','AnswerController@store');

    //drop answers of a given poll
    Route::delete('poll-answer/{poll}','AnswerController@drop');


    //Report
    Route::get('/voters-count/{poll}', 'PollReportController@pollVotersCount');
    Route::get('/option-percentage/{option}', 'PollReportController@optionsPercentage');
    Route::get('/report/{poll}', 'PollReportController@pollReport');
    Route::get('/report/{poll}/{userId}/{appId?}', 'PollReportController@userReport');
    Route::get('/report-adjectives/{poll}', 'PollReportController@fetchAdjectiveAnswers');

});
