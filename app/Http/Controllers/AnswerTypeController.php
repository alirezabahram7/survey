<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnswerTypeController extends Controller
{
    public function index(Request $request)
    {
        $rows = AnswerType::get();
        return $this->_outPut([
            'data' => $rows,
        ], 200);
    }
}
