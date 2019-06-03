<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;
use App\Poll;
use App\Option;

class PollReportController extends Controller
{
    public function pollVotersCount($pollId)
    {
        $poll = Poll::findOrFail($pollId);
        $votersCount=Answer::count('user_id');
        return response($votersCount);
    }

    public function optionsPercentage($optionId)
    {
        $option = Option::findOrFail($optionId);
        $count = $option->answers->count('option_id');
        $pollId = $option->question->poll->id;
        $pollVoters = json_decode($this->pollVotersCount($pollId));
        dd($pollVoters);
        $votersCount = $pollVoters['data']['voters_count'];

        $result = [
            'options_count' => $count,
            'option_percentage' => ($votersCount == 0 ? 0 : (($count / $votersCount) * 100))
        ];

        return response(new BasicResource($result), 201);
    }
}
