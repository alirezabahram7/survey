<?php

namespace Tests\Feature;

use Tests\PollTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePollTest extends PollTestCase
{

    protected $poll;

    protected function setUp(): void
    {
        parent::setUp();
        $this->poll=create('App\Poll');
    }

    public function test_guests_may_not_delete_a_poll()
    {
        $this->deletePoll()
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_delete_a_poll()
    {
        $this->deletePoll(true)
            ->assertStatus(204);
    }
}
