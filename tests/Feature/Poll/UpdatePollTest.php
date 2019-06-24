<?php

namespace Tests\Feature\Poll;

class UpdatePollTest extends PollTestCase
{
    protected $method = 'patch';

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUrl();
    }

    public function test_guests_may_not_update_a_poll()
    {
        $this->runRoute($this->method,'make',false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_update_a_poll()
    {
        $this->runRoute($this->method)
            ->assertStatus(200);
    }

    public function test_a_poll_cant_be_updated_if_it_doesnt_exists()
    {
        $this->url=$this->url.'9999999999999999999';
        $this->runRoute($this->method)
            ->assertStatus(404);
    }
}
