<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Poll;
use App\Option;

class PollReportController extends Controller
{
    /**
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function pollVotersCount(Poll $poll)
    {
        if (!$poll) {
            throw new ModelNotFoundException();
        }
        $votersCount = $poll->answers->count('user_id');
        return response($votersCount);
    }

    /**
     * @param Option $option
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function optionsPercentage(Option $option)
    {
        if (!$option) {
            throw new ModelNotFoundException();
        }
        $optionCount = $option->answers->count('option_id');
        $poll = $option->question->poll;
        $votersCount = $this->pollVotersCount($poll)->content();

        $result = [
            'options_count' => $optionCount,
            'option_percentage' => ($votersCount == 0 ? 0 : (($optionCount / $votersCount) * 100))
        ];

        return response(new BasicResource($result), 201);
    }
}
