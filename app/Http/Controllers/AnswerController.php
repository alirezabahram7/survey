<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\Poll;
use App\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Poll $poll)
    {
        $answers =$poll->answers;
        return response(new BasicCollectionResource($answers), 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request, Poll $poll)
    {
        $requestData = $request->all();

        foreach ($requestData as $i => $answer) {
            $question = Question::findOrFail($answer['question_id']);
            $answerItems = array(
                'user_id' => 1,//auth()->user()->id,
                'app_id' => $poll->app_id,
                'question_id' => $question->id,
                'option_id' => null,
                'answer' => null,
            );

            if ($question->answer_type_id >= 2) {
                foreach ($answer['answer'] as $optionId) {
                    $answerItems['option_id'] = ((int)$optionId == 0) ? null : $optionId;
                    Answer::create($answerItems);
                }
            } else {
                $answerItems['answer'] = $answer['answer'][0];
                Answer::create($answerItems);
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
     * @param Request $request
     * @param Poll $poll
     * @param Answer $answer
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Poll $poll,Answer $answer)
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

        return response(new BasicResource($requestData), 201);
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
        if (!$answer) {
            throw new ModelNotFoundException("Entry does not Found");
        }
        $answer->delete();
        return response('deleted', 204);
    }
}
