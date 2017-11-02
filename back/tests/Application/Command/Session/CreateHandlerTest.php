<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Session;

use App\Application\Command\Session\Create;
use App\Application\Command\Session\CreateHandler;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Session\Import\ContentImporter;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $folder = $this->prophesize(Folder::class);
        $uploadedFile = $this
            ->getMockBuilder(UploadedFile::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([tempnam(sys_get_temp_dir(), ''), 'zip'])
            ->getMock();

        // Mock
        $generator = $this->prophesize(Generator::class);
        $contentImporter = $this->prophesize(ContentImporter::class);
        $dateTime = new \DateTime();
        $generator->generateUuid()->shouldBeCalled()->willReturn('123-123-123');
        $contentImporter->importNewSession(
            $course->reveal(),
            '123-123-123',
            9,
            'title',
            $uploadedFile,
            $dateTime,
            $folder->reveal(),
            true,
            true
        )->shouldBeCalled();

        // Handler
        $create = new Create($course->reveal());
        $create->title = 'title';
        $create->rank = 9;
        $create->folder = $folder->reveal();
        $create->content = $uploadedFile;
        $create->needValidation = true;
        $create->enable = true;
        $handler = new CreateHandler($generator->reveal(), $contentImporter->reveal(), $dateTime);
        $handler->handle($create);
    }
}
