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

Route::post('login', function (Request $request) {

    if (auth()->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
        // Authentication passed...
        $user = auth()->user();
       $user->api_token =\Illuminate\Support\Str::random(60);
        $user->save();
        return $user;
    }

    return response()->json([
        'error' => 'Unauthenticated user',
        'code' => 401,
    ], 401);
});

Route::middleware('auth:api')->post('logout', function (Request $request) {

    if (auth()->user()) {
        $user = auth()->user();
        $user->api_token = null; // clear api token
        $user->save();

        return response()->json([
            'message' => 'Thank you for using our application',
        ]);
    }

    return response()->json([
        'error' => 'Unable to logout user',
        'code' => 401,
    ], 401);
});

Route::middleware('auth.fitamin')->group(function () {

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

    //Report
    Route::get('/voters-count/{poll}', 'PollReportController@pollVotersCount');
    Route::get('/option-percentage/{option}', 'PollReportController@optionsPercentage');
    Route::get('/report/{poll}', 'PollReportController@pollReport');
});
