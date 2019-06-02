<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\BasicCollectionResource;
use App\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        $question = Question::findOrFail($requestData['question_id']);

        foreach ($requestData['question_id'] as $i => $questionId) {
            if ($question->answer_type_id > 1) {
                foreach ($requestData['answer'][$i] as $optionId) {
                    Answer::create([
                        'user_id' => $requestData['user_id'],
                        'app_id' => $requestData['app_id'],
                        'question_id' => $questionId,
                        'option_id' => $optionId
                    ]);
                }
            } else {
                Answer::create([
                    'user_id' => $requestData['user_id'],
                    'app_id' => $requestData['app_id'],
                    'answer' => $requestData['answer'][$i],
                    'question_id' => $questionId
                ]);
            }
        }

        return response('ok', 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }
}
