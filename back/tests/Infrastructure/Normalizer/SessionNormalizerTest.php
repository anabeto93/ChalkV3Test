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
use App\Infrastructure\Normalizer\Session\FileNormalizer;
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
        $session = new Session('uuid', 5, 'session title', 'this is the content', $course, null, true, $createdAt);
        $file1 = $this->prophesize(Session\File::class);
        $file2 = $this->prophesize(Session\File::class);
        $session->setFiles([$file1->reveal(), $file2->reveal()]);

        // Mock
        $fileNormalizer = $this->prophesize(FileNormalizer::class);
        $fileNormalizer->normalize($file1->reveal())->shouldBeCalled()->willReturn(['file1']);
        $fileNormalizer->normalize($file2->reveal())->shouldBeCalled()->willReturn(['file2']);

        // Normalizer
        $sessionNormalier = new SessionNormalizer($fileNormalizer->reveal());
        $result = $sessionNormalier->normalize($session);

        $expected = [
            'uuid' => 'uuid',
            'rank' => 5,
            'title' => 'session title',
            'content' => 'this is the content',
            'validated' => true,
            'needValidation' => true,
            'createdAt' => $createdAt,
            'updatedAt' => $createdAt,
            'files' => [
                ['file1'],
                ['file2'],
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
