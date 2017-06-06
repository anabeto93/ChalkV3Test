<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer;

use App\Domain\Model\Course;
use App\Infrastructure\Normalizer\CourseNormalizer;
use PHPUnit\Framework\TestCase;

class CourseNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $dateTime = new \DateTime();
        $course = new Course("1234-azerty", "title", "teacherName", $dateTime, "description");

        $normalizer = new CourseNormalizer();
        $result = $normalizer->normalize($course);

        $expected = [
            'uuid' => '1234-azerty',
            'title' => 'title',
            'description' => 'description',
            'teacherName' => 'teacherName',
            'createdAt' => $dateTime,
        ];

        $this->assertEquals($expected, $result);
    }
}
