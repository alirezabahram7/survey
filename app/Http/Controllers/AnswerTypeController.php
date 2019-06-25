<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicCollectionResource;
use App\AnswerType;

class AnswerTypeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        $answerTypes = AnswerType::all();
        return response(new BasicCollectionResource($answerTypes),200);
    }
}
