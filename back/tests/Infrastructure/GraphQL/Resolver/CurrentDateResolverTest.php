<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\GraphQL\Resolver\CurrentDateResolver;
use PHPUnit\Framework\TestCase;

class CurrentDateResolverTest extends TestCase
{
    public function testResolveCurrentDate()
    {
        $dateTime = new \DateTime();

        $resolver = new CurrentDateResolver($dateTime);
        $result = $resolver->resolveCurrentDate();

        $this->assertEquals($dateTime, $result);
    }
}
