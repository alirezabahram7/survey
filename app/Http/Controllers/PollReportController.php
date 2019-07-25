<?php

namespace App\Http\Controllers;

use App\Answer;
use App\AnswerType;
use App\Http\Resources\AnswerCollectionResource;
use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Poll;
use App\Option;

class PollReportController extends Controller
{
    private $dateFrom, $dateTo;

    /**
     * PollReportController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setDatePeriod($request);
    }

    /**
     * @param Poll $poll
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function pollVotersCount(Poll $poll)
    {
        $votersCount = $poll->answers()
            ->distinct(['user_id', 'app_id'])
            ->whereBetween('answers.created_at', [$this->dateFrom, $this->dateTo])
            ->count(['answers.user_id', 'answers.app_id']);
        return response($votersCount, 200);
    }

    /**
     * @param Question $question
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function questionVotersCount(Question $question)
    {
        $votersCount = $question->answers()->distinct(['user_id', 'app_id'])
            ->betweenDates([$this->dateFrom, $this->dateTo])
            ->count(['answers.user_id', 'answers.app_id']);

        $optionPercentage = array();
        foreach ($question->options as $option) {
            $optionPercentage [$option->id] = $this->optionsPercentage($option)->original;
        }

        $result = [
            'question_id' => $question->id,
            'answewr_type_id' => $question->answer_type_id,
            'question_text' => $question->text,
            'question_voters_count' => $votersCount,
            'options' => $optionPercentage
        ];
        return response($result, 200);
    }

    /**
     * @param Option $option
     * @param string $sign
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function optionsPercentage(Option $option)
    {
        $optionCount = $option->answers()->
        distinct(['user_id', 'app_id', 'option_id'])
            ->betweenDates([$this->dateFrom, $this->dateTo])
            ->count([
                'answers.user_id',
                'answers.app_id',
                'answers.option_id'
            ]);
        $poll = $option->question->poll;
        $votersCount = $this->pollVotersCount($poll)->content();

        $result = [
            'question_id' => $option->question_id,
            'option_id' => $option->id,
            'option_text' => $option->text,
            'options_count' => $optionCount,
            'option_percentage' => ($votersCount == 0 ? 0 : (($optionCount / $votersCount) * 100))
        ];

        return response($result, 200);
    }

    /**
     * @param Poll $poll
     * @param string $dateFrom
     * @param string $dateTo
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function pollReport(Poll $poll)
    {
        $pollVotersCount = $this->pollVotersCount($poll);
        foreach ($poll->questions as $question) {
            $questionVoters[$question->id] = $this->questionVotersCount($question)->original;
        }

        $result = [
            'poll_title' => $poll->title,
            'voters_count' => $pollVotersCount->original,
            'questions' => $questionVoters
        ];
        return response(new BasicResource($result), 200);
    }

    public function userReport(Poll $poll, $userId, $appId = null)
    {
        if ($appId == null) {
            $appId = $poll->app_id;
        }

        $result = [
            'poll_id' => $poll->id,
            'poll_title' => $poll->title,
            'user_id' => $userId,
            'app_id' => $appId,
            'questions' => new AnswerCollectionResource($poll->answers->where('user_id', $userId)->where('app_id',
                $appId)),
            'answers' => $poll->answers->where('user_id', $userId)->where('app_id', $appId)
        ];

        return response(new BasicResource($result), 200);
    }

    /**
     * @param Request $request
     */
    protected function setDatePeriod(Request $request): void
    {
        $this->dateFrom = $request->has('date_from') ? $request->date_from : '1970-01-01';
        $this->dateTo = $request->has('date_to') ? $request->date_to : '3000-01-01';
    }

    /**
     * @param $pollId
     * @param int $perPage
     * @return \App\Http\Resources\BasicCollectionResource
     */
    public function fetchAdjectiveAnswers($pollId,$perPage = 20)
    {
        $answers = Answer::whereHas('question',function ($q) use ($pollId){
            $q->where('poll_id',$pollId)->adjectives();
        })->paginate($perPage);;

        return new BasicCollectionResource($answers);
    }
}
