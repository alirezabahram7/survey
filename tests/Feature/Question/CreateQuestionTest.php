<?php

namespace Tests\Feature\Question;

class CreateQuestionTest extends QuestionTestCase
{
    protected $method = 'post';

    public function test_a_guest_may_not_add_a_question()
    {
        $response = $this->runRoute($this->method, 'create', false);
        $response->assertStatus(401);

    }

    public function test_an_authenticated_user_can_add_a_question()
    {
        $response = $this->runRoute($this->method, 'create');
        $response->assertStatus(201);

    }

    public function test_an_optional_question_can_save_its_options()
    {
        $question = create($this->class);
        for($i=0;$i<4;$i++) {
            $question->options[$i] = create('App\Option', ['question_id' => $question->id]);
        }
        $this->addHeader(true);
        $this->post($this->url, $question->toArray())
            ->assertStatus(201);

    }

    public function test_a_question_requires_a_text()
    {
        $response = $this->runRoute($this->method, 'make', true, ['text' => null]);
        $response->assertSessionHasErrors('text');
    }

    public function test_a_question_requires_a_poll_id()
    {
        $response = $this->runRoute($this->method, 'make', true, ['poll_id' => null]);
        $response->assertSessionHasErrors('poll_id');
    }

    public function test_a_question_requires_a_answer_type_id()
    {
        $response = $this->runRoute($this->method, 'make', true, ['answer_type_id' => null]);
        $response->assertSessionHasErrors('answer_type_id');
    }
}
