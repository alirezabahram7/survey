<?php

namespace Tests\Feature\Question;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadQuestionTest extends QuestionTestCase
{
    protected $method = 'get';

    public function test_guests_may_not_read_questions()
    {
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_guests_may_not_read_a_question()
    {
        $this->hasIdInUrl = true;
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_read_questions()
    {
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(200)
            ->assertSee($this->model->title);

    }

    public function test_an_authenticated_user_can_read_a_question()
    {
        $this->hasIdInUrl = true;
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(200)
            ->assertSee($this->model->title);
    }

    public function test_a_question_cant_be_seen_if_it_doesnt_exists()
    {
        $this->url=$this->url.'9999999999999999999';
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(404);
    }

}
