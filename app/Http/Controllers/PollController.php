<?php

namespace App\Http\Controllers;

use App\Filters\PollFilter;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Poll;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        $apiKey = $request->header('x-api-key');

        $user = User::whereApiKey($apiKey)->first();

        //dd($request->server->all()['PHP_AUTH_USER']);
        $requestData = $request->all();
        /********************validation***********************/
        $this->validate($request, Poll::$rules);

        /***********************store************************/
        $requestData['app_id'] = $user->app_id;
        //dd($requestData);
        $newPoll = Poll::create($requestData);

        return response(new BasicResource($newPoll), 201);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $is_active_statuses = [0, 1];

        if ($request->actives) {
            $is_active_statuses = [1];
        }

        $callback = function ($q) use ($is_active_statuses) {
                       $q->whereIn('is_active', $is_active_statuses);
                  };

        $poll = Poll::where('id', $id)
            ->with('parent')
            ->with('children')
            ->with([
                'categories.questions' => function ($q) use ($is_active_statuses,$callback) {
                    $q->whereIn('is_active', $is_active_statuses)->whereIn('answer_type_id',[1,4])->orWhereHas(
                        'options', $callback)->with([
                        'options' => $callback
                    ]);
                }
            ])
            ->with([
                'questions' => function ($q) use ($is_active_statuses,$callback) {
                    $q->where('questions.category_id', '=', 0)->orWhere('questions.category_id', '=',
                        null)->whereIn('is_active', $is_active_statuses)->whereIn('answer_type_id',[1,4])->orWhereHas(
                        'options', $callback
                    )->with([
                        'options' => $callback
                    ]);
                }
            ])
            ->first();

        if (!$poll) {
            throw new ModelNotFoundException();
        }

        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
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
        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
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
    public function destroy(Request $request,Poll $poll)
    {
        if (!$poll) {
            throw new ModelNotFoundException();
        }
        if($poll->app_id != $request->app_id){
            throw new UnauthorizedHttpException('','not allowed');
        }
        if ($poll->is_deletable == 0) {
            throw new UnauthorizedHttpException('', 'it s not deletable');
        }
        $poll->delete();

        return response('deleted', 204);
    }

    public function test()
    {
        return response('data gone through');
    }
}
