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

        return response(new BasicCollectionResource($questions), 201);
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
        /********************validation***********************/
        $validator = Validator::make($requestData, Question::$rules);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors(),
            ], 400);
        }

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
            ->with('poll')
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

        /*به روز رسانی آیتم ها*/
        if (!empty($requestData['options'])) {
            /*درج آیتم های جدید*/
            foreach ($requestData['options'] AS $newOption) {
                if (array_key_exists("id", $newOption)) {
                    $option = Option::find($newOption['id']);
                    if ($option) {
                        $option->update($newOption);
                        continue;
                    }
                }
                $newOption['question_id'] = $question->id;
                Option::create($newOption);
            }
        }

        return response('Question Updated', 201);
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
