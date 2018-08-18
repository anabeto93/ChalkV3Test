<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class getTokenFromBearerTest extends TestCase
{
    /**
     * A basic test example.
     * @group unit
     * @return void
     */
    public function testGetTokenFromBearer()
    {
        $this->assertEquals('verkl5', getTokenFromBearer('verkl5~'.time()));
    }
}
