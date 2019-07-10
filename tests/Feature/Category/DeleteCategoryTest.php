<?php

namespace Tests\Feature\Category;

class DeleteCategoryTest extends CategoryTestCase
{
    protected $method = 'delete';

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasIdInUrl = true;
    }

    public function test_guests_may_not_delete_a_category()
    {
        $this->runRoute($this->method, 'create', false)
            ->assertStatus(401);
    }

    public function test_an_authenticated_user_can_delete_a_category()
    {
        $this->runRoute($this->method, 'create', true)
            ->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $this->model->id]);

    }

    public function test_a_category_cant_be_deleted_if_it_doesnt_exists()
    {
        $this->runRoute($this->method, 'create');

        $this->addHeader(true);

        $this->sendRequest($this->method)
            ->assertStatus(404);
    }
}
