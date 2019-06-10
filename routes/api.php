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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//app
Route::get('/app', 'AppController@index');
Route::get('/app/{app}', 'AppController@show');

Route::apiResources([
    'poll' => 'PollController',
    'category' => 'CategoryController',
    'question' => 'QuestionController',
    'option' => 'OptionController',
    '/{poll?}/answer' => 'AnswerController'
]);

Route::get('/answer-type', 'AnswerTypeController@index');

//Report
Route::get('/voters-count/{poll}', 'PollReportController@pollVotersCount');
Route::get('/option-percentage/{option}', 'PollReportController@optionsPercentage');