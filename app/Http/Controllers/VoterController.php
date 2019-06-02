<?php

namespace App\Http\Controllers;

use App\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoterController extends Controller
{
    public function index()
    {
        $rows = Voter::paginate(15);
        return $this->_outPut([
            'data' => [
                'data' => $rows->items(),
                'total' => $rows->total(),
            ]

        ], 200);

    }

    public function store(Request $request)
    {
        $requestData = $request->all();

        /********************validation***********************/
        $validator = Validator::make($requestData, [
            'app_id' => 'required|integer',
            'member_key' => 'required',
            'poll_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->_outPut([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $voter = Voter::where([
                'app_id' => $requestData['app_id'],
                'member_key' => $requestData['member_key'],
                'poll_id' => $requestData['poll_id']
            ]
        )
            ->first();
        if ($voter) {
            return $this->_outPut([
                'result' => false,
                'message' => 'کاربر تکراری هست!'
            ], 409);
        }
        /*******************************************************/


        $result = Voter::create($request->all());
        return $this->_outPut([
            'data' => $result,
        ], 201);
    }


}
