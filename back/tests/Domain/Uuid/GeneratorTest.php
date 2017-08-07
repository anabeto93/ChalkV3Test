<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Uuid;

use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testGenerateUuid()
    {
        $generator = new Generator();

        $array = [
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
            $generator->generateUuid(),
        ];

        $arrayUnique = array_unique($array);

        $this->assertEquals($array, $arrayUnique);
    }
}
