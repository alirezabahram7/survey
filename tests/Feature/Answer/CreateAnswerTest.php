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
    }

    public function test_a_guest_may_not_add_an_answer()
    {
        $response = $this->runRoute($this->method,'create',false);
        $response->assertStatus(401);

    }

 /*   public function test_an_authenticated_user_can_add_an_answer()
    {
        $response = $this->runRoute($this->method,'create');
        $response->assertStatus(201);

    }*/
}
