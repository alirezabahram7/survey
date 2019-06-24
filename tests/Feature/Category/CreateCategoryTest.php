<?php

namespace Tests\Feature\Category;

class CreateCategoryTest extends CategoryTestCase
{
    protected $method = 'post';

    public function test_guests_may_not_add_a_category()
    {
        $this->runRoute($this->method,'create',false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_add_a_category()
    {
        $this->runRoute($this->method,'create')
             ->assertStatus(201);
    }

    public function test_a_category_should_have_a_valid_poll_id()
    {
        $this->runRoute($this->method, 'make',true, ['poll_id' => 9999999999999999])
            ->assertStatus(404);
    }

    public function test_a_category_should_have_a_null_or_valid_parent_id()
    {
        $this->runRoute($this->method, 'make',true, ['parent_id' => 9999999999999999999])
            ->assertStatus(404);
    }
}
