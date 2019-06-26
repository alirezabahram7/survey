<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/26/2019
 * Time: 5:28 PM
 */

namespace App\Filters;


class Filters
{
    protected $request, $builder;
    protected $filters = [];

    /**
     * PostFilter constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        /*$this->getFilters()
            ->filter(function ($filter){
                return method_exists($this,$filter);
            })
            ->each(function ($filter,$value){
                $this->$filter($value);
            });*/
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $builder;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        //return collect($this->request->only($this->filters))->flip();
        return $this->request->only($this->filters);

    }
}