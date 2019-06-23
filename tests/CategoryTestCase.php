<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/23/2019
 * Time: 3:35 PM
 */

namespace Tests;


class CategoryTestCase extends TestCase
{
    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */


    /**
     * @param bool $hasHeader
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function publishCategory($hasHeader = false, $overrides = [])
    {
        $category = $this->_addCategory($overrides, 'make');

        $this->addHeader($hasHeader);

        $response = $this->post('/api/category', $category->toArray());

        return $response;
    }

    /**
     * @param $id
     * @param bool $hasHeader
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function editCategory($id, $hasHeader = false, $overrides = [])
    {
        $category = $this->_addCategory($overrides, 'make');

        $this->addHeader($hasHeader);

        $response = $this->patch('/api/category/' . $id, $category->toArray());
        return $response;
    }

    /**
     * @param bool $hasHeader
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function deleteCategory($hasHeader = false, $overrides = [])
    {
        $category = $this->_addCategory($overrides, 'create');


        $this->addHeader($hasHeader);

        $response = $this->delete('/api/category/' . $category->id);
        return $response;
    }

    /**
     * @param $overrides
     * @param string $addingType
     * @return mixed
     */
    private function _addCategory($overrides, $addingType = 'make')
    {
        if ($addingType == 'create') {
            $category = create('App\Category', $overrides);
            return $category;
        }
        $category = make('App\Category', $overrides);
        return $category;

    }
}