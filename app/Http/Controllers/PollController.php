<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\Http\Resources\PollCollectionResource;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Poll;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Class PollController
 * @package App\Http\Controllers
 */
class PollController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        $polls = Poll::all();
        return response(new BasicCollectionResource($polls), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        /********************validation***********************/
        $validator = Validator::make($requestData, Poll::$rules);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors(),
            ], 400);
        }
        /***********************store************************/
        $newPoll = Poll::create($requestData);

        return response(new BasicResource($newPoll), 201);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $poll = Poll::where('id', $id)
            ->with([
                'questions' => function ($q) {
                    $q->with('options');
                }
            ])
            ->first();
        if (!$poll) {
            throw new ModelNotFoundException();
        }

        return response(new BasicResource($poll), 200);
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
        $poll = Poll::where('id', $id)
            ->first();
        if(!$poll) {
            throw new ModelNotFoundException();
        }

        $requestData = $request->all();

        $result = $poll->update($requestData);
        if(!$result) {
            return new \Exception();
        }

        return response(new BasicResource($result),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poll = Poll::findOrFail($id)->first();
        $poll->delete();

        return response('deleted',204);
    }
}
