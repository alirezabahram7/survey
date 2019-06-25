<?php

namespace Tests\Feature\Answer;

use Tests\GeneralTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTestCase extends GeneralTestCase
{
    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected $pollId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->class = 'App\Answer';
        $this->url = '/api/'.$this->pollId.'/poll/';
    }
}
