<?php

namespace App\Http\Controllers;

use App\Answer;
use App\AnswerType;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\Poll;
use App\Question;
use App\Traits\SaveAnswer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AnswerController extends Controller
{
    use SaveAnswer;
    /**
     * Display a listing of the resource.
     *
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Poll $poll)
    {
        $answers = $poll->answers;
        return response(new BasicCollectionResource($answers), 200);
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
       $this->saveAllAnswers($request, $poll);
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
    public function update(Request $request, Poll $poll, Answer $answer)
    {
        $this->checkAppAuthorization($request, $poll);
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
        } else {
            $answerItems['answer'] = $requestData['answer'];
        }
        $answer->update($answerItems);
        return response('Answer Updated', 201);
    }

    /**
     * @param Request $request
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function drop(Request $request, Poll $poll)
    {
        $this->checkAppAuthorization($request, $poll);

        $poll->answers()->delete();

        return response('deleted', 204);
    }
}
