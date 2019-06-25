<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/23/2019
 * Time: 3:35 PM
 */

namespace Tests\Feature\Poll;


use Tests\GeneralTestCase;

class PollTestCase extends GeneralTestCase
{
    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->class = 'App\Poll';
        $this->url = '/api/poll/';
    }

}