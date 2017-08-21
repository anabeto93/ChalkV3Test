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
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Infrastructure\Normalizer\CourseNormalizer;
use App\Infrastructure\Normalizer\FolderNormalizer;
use App\Infrastructure\Normalizer\SessionNormalizer;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class CourseNormalizerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $folderNormalizer;

    /** @var ObjectProphecy */
    private $sessionNormalizer;

    public function setUp()
    {
        $this->folderNormalizer = $this->prophesize(FolderNormalizer::class);
        $this->sessionNormalizer = $this->prophesize(SessionNormalizer::class);
    }

    public function testNormalize()
    {
        $dateTime = new \DateTime();
        $course = new Course("1234-azerty", "title", "teacherName", 'University', true, $dateTime, "description");
        $folder = new Folder('123456789', 'folder title', $course, $dateTime);
        $session1 = new Session('098765432', 1, 'session 1', 'content 1', $course, $folder, true, $dateTime);
        $session2 = new Session('ZERTYUIOIUYTRE', 2, 'session 2', 'content 2', $course, $folder, true, $dateTime);
        $course->setSessions([$session1, $session2]);

        $this->setFolderId($folder, 2);

        // Mock
        $this->folderNormalizer->normalize($folder)->shouldBeCalled()->willReturn(['uuid' => '123456789']);
        $this->sessionNormalizer->normalize($session1)->shouldBeCalled()->willReturn(['uuid' => '098765432']);
        $this->sessionNormalizer->normalize($session2)->shouldBeCalled()->willReturn(['uuid' => 'ZERTYUIOIUYTRE']);

        // Normalizer
        $normalizer = new CourseNormalizer(
            $this->folderNormalizer->reveal(),
            $this->sessionNormalizer->reveal()
        );
        $result = $normalizer->normalize($course);

        $expected = [
            'uuid' => '1234-azerty',
            'title' => 'title',
            'description' => 'description',
            'university' => 'University',
            'teacherName' => 'teacherName',
            'createdAt' => $dateTime,
            'folders' => [
                2 => [
                    'uuid' => '123456789',
                    'sessions' => [
                        [
                            'uuid' => '098765432'
                        ],
                        [
                            'uuid' => 'ZERTYUIOIUYTRE'
                        ],
                    ]
                ],
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testNormalizeNoFolder()
    {
        $dateTime = new \DateTime();
        $course = new Course("1234-azerty", "title", "teacherName", 'University', true, $dateTime, "description");
        $session1 = new Session('098765432', 2, 'session 1', 'content 1', $course, null, false, $dateTime);
        $session2 = new Session('ZERTYUIOIUYTRE', 3, 'session 2', 'content 2', $course, null, true, $dateTime);
        $course->setSessions([$session1, $session2]);

        // Mock
        $this->folderNormalizer->normalizeDefaultFolder()->shouldBeCalled()->willReturn(['uuid' => 'default']);
        $this->sessionNormalizer->normalize($session1)->shouldBeCalled()->willReturn(['uuid' => '098765432']);
        $this->sessionNormalizer->normalize($session2)->shouldBeCalled()->willReturn(['uuid' => 'ZERTYUIOIUYTRE']);

        // Normalizer
        $normalizer = new CourseNormalizer(
            $this->folderNormalizer->reveal(),
            $this->sessionNormalizer->reveal()
        );
        $result = $normalizer->normalize($course);

        $expected = [
            'uuid' => '1234-azerty',
            'title' => 'title',
            'description' => 'description',
            'teacherName' => 'teacherName',
            'university' => 'University',
            'createdAt' => $dateTime,
            'folders' => [
                'default' => [
                    'uuid' => 'default',
                    'sessions' => [
                        [
                            'uuid' => '098765432'
                        ],
                        [
                            'uuid' => 'ZERTYUIOIUYTRE'
                        ],
                    ]
                ],
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @param Folder $folder
     * @param int    $id
     */
    private function setFolderId(Folder $folder, int $id)
    {
        $reflection = new \ReflectionClass(Folder::class);
        $property= $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($folder, $id);
        $property->setAccessible(false);
    }
}
