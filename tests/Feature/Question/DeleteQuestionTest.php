<?php

namespace Tests\Feature\Question;

class DeleteQuestionTest extends QuestionTestCase
{

    protected $method = 'delete';

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasIdInUrl = true;
    }

    public function test_guests_may_not_delete_a_question()
    {
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_delete_a_question()
    {
        $this->runRoute($this->method, 'create')
            ->assertStatus(204);
    }

    public function test_a_question_cant_be_deleted_if_it_doesnt_exists()
    {
        $this->runRoute($this->method, 'create');

        $this->addHeader(true);

        $this->sendRequest($this->method)
            ->assertStatus(404);
    }
}
