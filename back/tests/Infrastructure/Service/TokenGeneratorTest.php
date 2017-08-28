<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Service;

use App\Infrastructure\Service\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    public function testTokenGeneration()
    {
        $tokenGenerator = new TokenGenerator();
        $result = $tokenGenerator->generateToken(6);

        $this->assertEquals(6, strlen($result));

        $this->assertNotRegExp('/[1OlI0]/', $result);
    }
}
