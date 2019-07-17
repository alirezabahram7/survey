<?php

namespace App\Http\Controllers;

use App\Filters\QuestionFilter;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\Poll;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\Validator;
use App\Option;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param QuestionFilter $filters
     * @return \Illuminate\Http\Response
     */
    public function index(QuestionFilter $filters)
    {
        $questions = Question::filter($filters)->with('poll')
            ->with('options')->get();

        return response(new BasicCollectionResource($questions), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $poll =Poll::findOrFail($request->poll_id);

        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
        }
        $requestData = $request->all();
        /********************validation***********************/
        $this->validate($request, Question::$rules);

        /*******************************************************/
        $newQuestion = Question::create($requestData);

        if ($newQuestion->answer_type_id > 1) {
            /*گزینه های انتخابی نیز ارسال شده باشد.*/
            if (!empty($requestData['options'])) {
                foreach ($requestData['options'] AS $option) {
                    $option['question_id'] = $newQuestion->id;
                    Option::create($option);
                }
            }
        }

        return response('Question Saved', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $question = Question::where('id', $id);

        $is_active_statuses = [0, 1];

        if ($request->actives) {
            $is_active_statuses = [1];
        }

        $callback = function ($q) use ($is_active_statuses) {
            $q->whereIn('is_active', $is_active_statuses);
        };

        $question = $question->whereHas('options',$callback)->with([
            'options' => $callback
        ])
            ->first();

        if (!$question) {
            throw new ModelNotFoundException();
        }

        $poll =Poll::findOrFail($question->poll_id);

        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
        }
        return response(new BasicResource($question), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Question $question
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        if (!$question) {
            throw new ModelNotFoundException();
        }
        $poll =Poll::findOrFail($question->poll_id);

        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
        }
        $requestData = $request->all();
        $question->update($requestData);

        /*replace options*/
        if (!empty($requestData['options'])) {
            $question->options()->delete();
            $question->options()->createMany($requestData['options']);
        }

        return response('Question Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request,Question $question)
    {
        if (!$question) {
            throw new ModelNotFoundException();
        }
        $poll =Poll::findOrFail($question->poll_id);

        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
        }
        $question->delete();

        return response('deleted', 204);
    }
}
