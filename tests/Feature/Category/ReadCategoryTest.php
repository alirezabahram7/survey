<?php

namespace Tests\Feature\Category;

class ReadCategoryTest extends CategoryTestCase
{
    protected $method = 'get';

    public function test_guests_may_not_read_categories()
    {
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_guests_may_not_read_a_category()
    {
        $this->hasIdInUrl = true;
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_read_categories()
    {
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(200)
            ->assertSee($this->model->title);

    }

    public function test_an_authenticated_user_can_read_a_category()
    {
        $this->hasIdInUrl = true;
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(200)
            ->assertSee($this->model->title);
    }

    public function test_a_category_cant_be_seen_if_it_doesnt_exists()
    {
        $this->url=$this->url.'9999999999999999999';
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(404);
    }
}
