<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/26/2019
 * Time: 5:29 PM
 */

namespace App\Filters;


class PollFilter extends Filter
{
    protected $filters = ['app_id'];

    /**
     * Filter Query By a given username
     *
     * @param $appId
     * @return mixed
     */
    public function app_id($appId)
    {
        return $this->builder->where('app_id', $appId);
    }
}