<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/23/2019
 * Time: 3:35 PM
 */

namespace Tests\Feature\Question;


use Tests\GeneralTestCase;

class QuestionTestCase extends GeneralTestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->class = 'App\Question';
        $this->url = '/api/question/';
    }

}