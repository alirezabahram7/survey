<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\Validator;
use App\Option;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\AnswerType;

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

        return response(new BasicResource($newQuestion), 201);
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::where('id', $id)
            ->first();

        if (!$question) {
            throw new ModelNotFoundException();
        }

        $requestData = $request->all();
        $result = $question->update($requestData);
        if (!$result) {
            return response([
                'message' => 'خطا در عملیات مجددا سعی کنید!',
            ], 501);
        }

        /*به روز رسانی آیتم ها*/
        if (!empty($requestData['options'])) {
            /*حذف آیتم های قبلی*/
            $result = Option::where(['question_id' => $question->id])->delete();
            if (!$result) {
                return response([
                    'message' => 'خطا در عملیات مجددا سعی کنید!',
                ], 501);
            }
            /*درج آیتم های جدید*/
            foreach ($requestData['options'] AS $option) {
                $option['question_id'] = $question->id;
                Option::create($option);
            }
            return response(new BasicResource($question), 201);
        }

        return response(new BasicResource($question), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id)->first();
        $question->delete();

        return response('deleted', 204);
    }
}
