<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer\Session;

use App\Domain\Model\Session;
use App\Domain\Model\Session\File;
use App\Infrastructure\Normalizer\Session\FileNormalizer;
use App\Infrastructure\Service\UrlGenerator;
use PHPUnit\Framework\TestCase;

class FileNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        // Context
        $dateTime = new \DateTime();
        $session = $this->prophesize(Session::class);
        $file = new File($session->reveal(), '/path/to/file.jpg', 12345, $dateTime);
        // Mock
        $urlGenerator = $this->prophesize(UrlGenerator::class);
        $urlGenerator->getBaseUrl()->shouldBeCalled()->willReturn('https://api.chalkboardeducation.vm');

        $fileNormalizer = new FileNormalizer($urlGenerator->reveal());
        $result = $fileNormalizer->normalize($file);

        $expected = [
            'url' => 'https://api.chalkboardeducation.vm/path/to/file.jpg',
            'size' => 12345,
            'createdAt' => $dateTime,
            'updatedAt' => $dateTime
        ];

        $this->assertEquals($expected, $result);
    }
}
