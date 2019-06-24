<?php

namespace Tests\Feature\Category;

class UpdateCategoryTest extends CategoryTestCase
{
    protected $method = 'patch';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUrl();
    }

    public function test_guests_may_not_update_a_category()
    {
        $this->runRoute($this->method,'make',false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_update_a_category()
    {
        $this->runRoute($this->method)
            ->assertStatus(200);
    }

    public function test_a_category_cant_be_updated_if_it_doesnt_exists()
    {
        $this->url=$this->url.'9999999999999999999';
        $this->runRoute($this->method)
            ->assertStatus(404);
    }
}
