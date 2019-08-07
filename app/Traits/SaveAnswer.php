<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 7/25/2019
 * Time: 4:45 PM
 */

namespace App\Traits;

use App\Answer;
use App\AnswerType;
use App\Poll;
use App\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait SaveAnswer
{
    /**
     * @param Request $request
     * @param Poll $poll
     * @return array
     */
    protected function saveAllAnswers(Request $request, Poll $poll): void
    {
        // check for range and being numeric for scoring answers
        //...

        $this->checkAppAuthorization($request, $poll);

        $requestData = $request->answers;

        $answerItems = array(
            'user_id' => $request->user_id,
            'app_id' => $poll->app_id,
            'question_id' => null,
            'option_id' => null,
            'answer' => null,
        );

        foreach ($requestData as $i => $answer) {

            $question = Question::findOrFail($answer['question_id']);

            $answerItems['question_id'] = $question->id;

            $this->insertAnswer($question, $answer, $answerItems);
        }

    }

    /**
     * @param Request $request
     * @param Poll $poll
     */
    protected function checkAppAuthorization(Request $request, Poll $poll): void
    {
        if ($poll->app_id != $request->app_id) {
            throw new UnauthorizedHttpException('', 'not allowed');
        }
    }

    /**
     * @param $question
     * @param $answer
     * @param array $answerItems
     * @return array
     */
    protected function insertAnswer($question, $answer, array $answerItems): void
    {
        foreach ($answer['answer'] as $answer) {
            $answerItems = $this->setAnswerByType($question, $answer, $answerItems);
            Answer::create($answerItems);
        }
    }

    /**
     * @param $question
     * @param $answer
     * @param array $answerItems
     * @return array
     */
    protected function setAnswerByType($question, $answer, array $answerItems): array
    {
        switch ($question->answer_type_id) {
            case AnswerType::ADJECTIVE:
            case AnswerType::SCORING:
                $answerItems['answer'] = $answer;
                break;
            case AnswerType::RADIOBUTTON:
            case AnswerType::CHECKBOX:
                $answerItems['option_id'] = ((int)$answer == 0) ? null : $answer;
                break;
            case AnswerType::MIXED:
                if (gettype($answer) != 'integer') {
                    $answerItems['answer'] = $answer;
                } else {
                    $answerItems['option_id'] = ((int)$answer == 0) ? null : $answer;
                }
                break;
            default:
                break;
        }
        return $answerItems;
    }
}