<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 6/23/2019
 * Time: 5:09 PM
 */

namespace Tests;


class GeneralTestCase extends TestCase
{
    protected $model,$apiKey,$class,$url,$hasIdInUrl=false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = "81ecff074e9714a888c3d727f20fc1b2341226e0";
    }

    /**
     * @param $hasHeader
     */
    protected function addHeader($hasHeader): void
    {
        if ($hasHeader) {
            $this->withHeader('x-api-key', $this->apiKey);
        }
    }

    /**
     * @param $class
     * @param $overrides
     * @param string $addingType
     * @return mixed
     */
    protected function addModel($overrides, $addingType = 'make')
    {
        if ($addingType == 'create') {
            $model = create($this->class, $overrides);
            return $model;
        }
        $model = make($this->class, $overrides);
        return $model;

    }

    /**
     * @param $class
     * @param $url
     * @param $method
     * @param bool $hasHeader
     * @param array $overrides
     * @param string $addingType
     * @return mixed
     */
    protected function runRoute($method,$addingType='make', $hasHeader = true, $overrides = [])
    {

        $this->model = $this->addModel($overrides, $addingType);

        $this->addHeader($hasHeader);
        //dd($this->url);
        $response = $this->sendRequest($method);

        return $response;
    }

    /**
     * @param $method
     * @param $this->model
     * @return mixed
     */
    protected function sendRequest($method)
    {
        if($this->hasIdInUrl){
            $this->url=$this->url.$this->model->id;
        }
        //dd($this->model->toArray());
        $response = $this->$method($this->url, $this->model->toArray());
        return $response;
    }

    /**
     *
     */
    protected function setUrl(): void
    {
        $model = create($this->class);

        $this->url = $this->url . $model->id;
    }
}