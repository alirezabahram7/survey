<?php

namespace Tests\Feature;

use Tests\CategoryTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCategoryTest extends CategoryTestCase
{
    public function test_guests_may_not_add_a_category()
    {
        $this->publishCategory()
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_add_a_category()
    {
        $this->publishCategory(true)
            ->assertStatus(201);
    }

    public function test_a_category_should_have_a_valid_poll_id()
    {
        $this->publishCategory(true, ['poll_id' => 9999999999999999])
            ->assertStatus(404);
    }

    public function test_a_category_should_have_a_null_or_valid_parent_id()
    {
        $this->publishCategory(true,['parent_id'=>9999999999999999999])
            ->assertStatus(404);
    }
}
