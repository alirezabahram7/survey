<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\Option;

class PollReportController extends Controller
{
    public function pollVotersCount($pollId){
        $poll = Poll::find($pollId);
        return response()->json(['data' =>['voters_count' => $poll->voters->count('id')]]);
    }

    public function optionsPercentage($optionId){
        $option = Option::find($optionId);
        $count = $option->optionVoters->count('id');
        $pollId = $option->question->poll->id;
        $pollVoters = json_decode($this->pollVotersCount($pollId));
        $votersCount = $pollVoters['data']['voters_count'];
        return response()->json(['data' =>
            [
                'options_count' => $count,
                'option_percentage' => ($votersCount==0 ?  0: (($count/$votersCount)*100))
            ]]);
    }
}
