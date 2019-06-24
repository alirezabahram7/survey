<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/23/2019
 * Time: 3:35 PM
 */

namespace Tests\Feature\Category;


use Tests\GeneralTestCase;

class CategoryTestCase extends GeneralTestCase
{
    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->class = 'App\Category';
        $this->url = '/api/category/';
    }

}