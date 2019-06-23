<?php

namespace Tests\Feature;

use Tests\PollTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePollTest extends PollTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_a_guest_may_not_add_a_poll()
    {
        $response = $this->publishPoll();
        $response->assertStatus(401);

    }

    public function test_an_authenticated_user_can_add_a_poll()
    {
        $response = $this->publishPoll(true);
        $response->assertStatus(201);

    }

    public function test_a_poll_requires_a_title()
    {
        $response = $this->publishPoll(true,['title' => null]);
        $response->assertSessionHasErrors('title');
    }

    public function test_a_poll_requires_an_app_id()
    {
        $response = $this->publishPoll(true,['app_id' => null]);
        $response->assertSessionHasErrors('app_id');
    }
}
