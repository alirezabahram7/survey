<?php

namespace Tests\Feature\Poll;

class DeletePollTest extends PollTestCase
{

    protected $method = 'delete';

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasIdInUrl = true;
    }

    public function test_guests_may_not_delete_a_poll()
    {
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_delete_a_poll()
    {
        $this->runRoute($this->method, 'create')
            ->assertStatus(204);
        $this->assertDatabaseMissing('polls', ['id' => $this->model->id]);
    }

    public function test_a_poll_cant_be_deleted_if_it_doesnt_exists()
    {
        $this->runRoute($this->method, 'create');

        $this->addHeader(true);

        $this->sendRequest($this->method)
            ->assertStatus(404);
    }

    public function test_a_poll_cant_be_deleted_if_it_is_not_deletable()
    {
        $this->runRoute($this->method, 'create', true, ['is_deletable' => 0])
            ->assertStatus(401);
    }
}
