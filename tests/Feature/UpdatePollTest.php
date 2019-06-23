<?php

namespace Tests\Feature;

use Tests\PollTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePollTest extends PollTestCase
{

    public function test_guests_may_not_update_a_poll()
    {
        $this->editPoll(3)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_update_a_post()
    {
        $this->editPoll(3,true)
            ->assertStatus(200);
    }

}
