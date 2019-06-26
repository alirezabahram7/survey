<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/26/2019
 * Time: 5:29 PM
 */

namespace App\Filters;


class PollFilters extends Filters
{
    protected $filters = ['app_id'];

    /**
     * Filter Query By a given username
     *
     * @param string $username
     * @return mixed
     */
    public function by($username)
    {
        $user = Author::where('name', $username)->firstOrFail();
        return $this->builder->where('author_id', $user->id);
    }
}