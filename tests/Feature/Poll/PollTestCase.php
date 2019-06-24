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
    /**
     * @param $id
     * @param bool $hasHeader
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function editPoll($id, $hasHeader = false, $overrides = [])
    {
        $poll = $this->addModel('App\Poll', $overrides, 'make');

        $this->addHeader($hasHeader);

        $response = $this->patch('/api/poll/' . $id, $poll->toArray());
        return $response;
    }

}