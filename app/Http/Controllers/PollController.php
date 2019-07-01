<?php

namespace App\Http\Controllers;

use App\Filters\PollFilter;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Poll;

/**
 * Class PollController
 * @package App\Http\Controllers
 */
class PollController extends Controller
{

    /**
     * @param PollFilter $filters
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(PollFilter $filters)
    {
        $polls = Poll::filter($filters)->get();
        return response(new BasicCollectionResource($polls), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        /********************validation***********************/
        $this->validate($request, Poll::$rules);

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
            ->with('parent')
            ->with('children')
            ->with([
                'categories.questions' => function ($q) {
                    $q->with('options');
                }
            ])
            ->with([
                'questions' => function ($q) {
                    $q->where('questions.category_id', '=', 0)->orWhere('questions.category_id', '=',
                        null)->with('options');
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
     * @param Request $request
     * @param Poll $poll
     * @return \Exception|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Poll $poll)
    {
        if (!$poll) {
            throw new ModelNotFoundException();
        }

        $requestData = $request->all();

        $poll->update($requestData);

        return response('Poll Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Poll $poll)
    {
        if (!$poll) {
            throw new ModelNotFoundException();
        }
        $poll->delete();

        return response('deleted', 204);
    }
}
