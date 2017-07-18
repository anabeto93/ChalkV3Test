<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer;

use App\Domain\Model\Session;
use App\Infrastructure\Normalizer\SessionNormalizer;
use PHPUnit\Framework\TestCase;
use Tests\Factory\CourseFactory;

class SessionNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        // Context
        $createdAt = new \DateTime();
        $course = CourseFactory::create();
        $session = new Session('uuid', 'session title', 'this is the content', $course, null, $createdAt);

        // Normalizer
        $sessionNormalier = new SessionNormalizer();
        $result = $sessionNormalier->normalize($session);

        $expected = [
            'uuid' => 'uuid',
            'title' => 'session title',
            'content' => 'this is the content',
            'createdAt' => $createdAt,
            'updatedAt' => $createdAt
        ];

        $this->assertEquals($expected, $result);
    }
}
