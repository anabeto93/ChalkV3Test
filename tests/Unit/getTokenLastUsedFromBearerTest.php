<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class getTokenLastUsedFromBearerTest extends TestCase
{
    /**
     * A basic test example.
     * @group unit
     * @return void
     */
    public function testGetTokenLastUsedFromBearer()
    {
        $timestamp = time();
        $this->assertEquals($timestamp, getTokenLastUsedFromBearer('verkl5~'.$timestamp));
    }
}
