<?php

namespace Tests\Feature\Answer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAnswerTest extends AnswerTestCase
{
    protected $method = 'post';
    protected function setUp(): void
    {
        parent::setUp();
        $this->pollId = 4;
    }

    public function test_a_guest_may_not_add_a_answer()
    {
        $response = $this->runRoute($this->method,'create',false);
        $response->assertStatus(401);

    }

    public function test_an_authenticated_user_can_add_a_poll()
    {
        $response = $this->runRoute($this->method,'create');
        $response->assertStatus(201);

    }

    public function test_a_poll_requires_a_title()
    {
        $response = $this->runRoute($this->method,'make',true,['title' => null]);
        $response->assertSessionHasErrors('title');
    }

    public function test_a_poll_requires_an_app_id()
    {
        $response = $this->runRoute($this->method,'make',true,['app_id' => null]);
        $response->assertSessionHasErrors('app_id');
    }
}
