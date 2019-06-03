<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
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
        $answers = Answer::all();
        return response(new BasicCollectionResource($answers), 201);
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

        foreach ($requestData['question_id'] as $i => $questionId) {

            $question = Question::findOrFail($questionId);

            $answer = array(
                'user_id' => $requestData['user_id'],
                'app_id' => $requestData['app_id'],
                'question_id' => $questionId,
                'option_id' => null,
                'answer' => null,
            );

            if ($question->answer_type_id >= 2) {
                foreach ($requestData['answer'][$i] as $optionId) {
                    $answer['option_id'] = ((int)$optionId == 0) ? null : $optionId;
                    Answer::create($answer);
                }
            } else {
                $answer['answer'] = $requestData['answer'][$i][0];
                Answer::create($answer);
            }
        }

        return response('Answers saved', 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $answer = Answer::findOrFail($id);

        if (!$answer) {
            throw new ModelNotFoundException("Entry does not Found");
        }

        return response(new BasicResource($answer), 201);
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
        $requestData = $request->all();
        $question = Question::findOrFail($requestData['question_id']);

        $answerItems = array(
            'user_id' => $requestData['user_id'],
            'app_id' => $requestData['app_id'],
            'question_id' => $requestData['question_id'],
            'option_id' => null,
            'answer' => null,
        );

        if ($question->answer_type_id >= 2) {
            $answerItems['option_id'] = $requestData['answer'];
            $answer->update($answerItems);
        } else {
            $answerItems['answer'] = $requestData['answer'];
            $answer->update($answerItems);
        }

        return response('Answer updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer $answer
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Answer $answer)
    {
        if(!$answer){
            throw new ModelNotFoundException("Entry does not Found");
        }
        $answer->delete();
        return response('deleted', 204);
    }
}
