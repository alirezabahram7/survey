<?php

namespace App\Http\Resources;

use http\Env\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AnswerCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $data = $this->collection->transform(function ($answer) {

                return [
                    'question_text' => $answer->question->text,
                    'answer' => $answer->answer,
                    'options' => $answer->question->optionsText,
                    'answered_option' => $answer->optionText
                ];
        });

        return $data;
    }
}
