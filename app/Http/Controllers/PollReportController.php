<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\Question;
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
        $votersCount = $poll->answers()->distinct(['user_id', 'app_id'])->count(['user_id', 'app_id']);
        return response($votersCount, 200);
    }

    /**
     * @param Question $question
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function questionVotersCount(Question $question)
    {
        if (!$question) {
            throw new ModelNotFoundException();
        }
        $votersCount = $question->answers()->distinct(['user_id', 'app_id'])->count(['user_id', 'app_id']);

        $result = [
            'question_id' => $question->id,
            'question_voters_count' => $votersCount
        ];
        return response($result, 200);
    }

    /**
     * @param Option $option
     * @param string $sign
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function optionsPercentage(Option $option, $sign = 'data')
    {
        if (!$option) {
            throw new ModelNotFoundException();
        }
        $optionCount = $option->answers()->distinct(['user_id', 'app_id', 'option_id'])->count([
            'user_id',
            'app_id',
            'option_id'
        ]);
        $poll = $option->question->poll;
        $votersCount = $this->pollVotersCount($poll)->content();

        $result = [
            'option_id' => $option->id,
            'options_count' => $optionCount,
            'option_percentage' => ($votersCount == 0 ? 0 : (($optionCount / $votersCount) * 100))
        ];

        return response(new BasicResource($result, $sign), 200);
    }

    /**
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function pollReport(Poll $poll)
    {
        $pollVotersCount = $this->pollVotersCount($poll);
        foreach ($poll->questions as $question) {
            $questionVoters[$question->id] = $this->questionVotersCount($question)->original;
            foreach ($question->options as $option) {
                $optionPercentage [$option->id] = $this->optionsPercentage($option)->original;
            }
        }

        $result = [
            'voters_count' => $pollVotersCount->original,
            'questions' => [
                'question_voters_count' => $questionVoters,
                'options' => $optionPercentage
            ]
        ];
        return response(new BasicResource($result), 200);
    }

}
