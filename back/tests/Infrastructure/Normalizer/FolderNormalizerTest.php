<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer;

use App\Domain\Model\Folder;
use App\Domain\Model\Institution;
use App\Infrastructure\Normalizer\FolderNormalizer;
use PHPUnit\Framework\TestCase;
use Tests\Factory\CourseFactory;

class FolderNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        // Context
        $dateTime = new \DateTime();
        $institution = new Institution('uuid-uni', 'Chalkboard', $dateTime);
        $course = CourseFactory::create($institution);
        $folder = new Folder('folder-uuid', 1, 'folder title', $course, $dateTime);

        // Normalizer
        $folderNormalier = new FolderNormalizer($dateTime);
        $result = $folderNormalier->normalize($folder);

        $expected = [
            'uuid' => 'folder-uuid',
            'rank' => 1,
            'title' => 'folder title',
            'updatedAt' => $dateTime,
        ];

        $this->assertEquals($expected, $result);
    }

    public function testNormalizeDefault()
    {
        // Context
        $dateTime = new \DateTime();

        // Normalizer
        $folderNormalier = new FolderNormalizer($dateTime);
        $result = $folderNormalier->normalize(null);

        $expected = [
            'uuid' => 'default',
            'rank' => 0,
            'title' => 'default',
            'updatedAt' => $dateTime,
        ];

        $this->assertEquals($expected, $result);
    }
}
