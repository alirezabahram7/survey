<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\Validator;
use App\Option;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with('poll')
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::where('id', $id)
            ->with('options')
            ->first();

        if (!$question) {
            throw new ModelNotFoundException();
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
    public function destroy(Question $question)
    {
        if (!$question) {
            throw new ModelNotFoundException();
        }

        $question->delete();

        return response('deleted', 204);
    }
}
