<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/23/2019
 * Time: 3:35 PM
 */

namespace Tests;


class PollTestCase extends TestCase
{
    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */


    /**
     * @param bool $hasHeader
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function publishPoll($hasHeader = false, $overrides = [])
    {
        $poll = $this->_addPoll($overrides, 'make');

        if ($hasHeader) {
            $this->withHeader('x-api-key', $this->apiKey);
        }

        $response = $this->post('/api/poll', $poll->toArray());

        return $response;
    }

    /**
     * @param $overrides
     * @param string $addingType
     * @return mixed
     */
    private function _addPoll($overrides, $addingType = 'make')
    {
        if ($addingType == 'create') {
            $poll = create('App\Poll', $overrides);
            return $poll;
        }
        $poll = make('App\Poll', $overrides);
        return $poll;

    }
}