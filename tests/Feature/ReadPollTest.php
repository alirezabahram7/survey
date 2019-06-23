<?php

namespace Tests\Feature;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadPollTest extends TestCase
{
    protected $poll,$apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->poll= create('App\Poll');
    }

    public function test_guests_may_not_read_polls()
    {
        $response=$this->get('/api/poll');
        $response->assertStatus(401);
    }

    public function test_guests_may_not_read_a_poll()
    {
        $response=$this->get('/api/poll/'.$this->poll->id);
        $response->assertStatus(401);
    }
    public function test_an_authenticated_user_can_read_polls()
    {
        $this->withHeader('x-api-key',$this->apiKey);
        $response=$this->get('/api/poll');
        $response->assertSee($this->poll->title);
    }
     public function test_an_authenticated_user_can_read_a_poll()
         {
             $this->withHeader('x-api-key',$this->apiKey);
             $response=$this->get('/api/poll/'.$this->poll->id);
             $response->assertSee($this->poll->title);
         }


}
