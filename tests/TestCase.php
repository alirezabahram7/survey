<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = md5('fitamin-survey');
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
}
