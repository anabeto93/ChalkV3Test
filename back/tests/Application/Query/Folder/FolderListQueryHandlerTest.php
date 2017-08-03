<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Query\Folder;

use App\Application\Query\Folder\FolderListQuery;
use App\Application\Query\Folder\FolderListQueryHandler;
use App\Application\View\Folder\FolderView;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FolderListQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $folder1 = $this->prophesize(Folder::class);
        $folder2 = $this->prophesize(Folder::class);
        $folder1->getId()->willReturn(1);
        $folder2->getId()->willReturn(2);
        $folder1->getTitle()->willReturn('title 1');
        $folder2->getTitle()->willReturn('title 2');

        // Mock
        $folderRepository = $this->prophesize(FolderRepositoryInterface::class);
        $folderRepository
            ->findByCourse($course->reveal())
            ->shouldBeCalled()
            ->willReturn([$folder1->reveal(), $folder2->reveal()])
        ;

        // Handler
        $queryHandler = new FolderListQueryHandler($folderRepository->reveal());
        $result = $queryHandler->handle(new FolderListQuery($course->reveal()));

        $expected = [
            new FolderView(1, 'title 1'),
            new FolderView(2, 'title 2'),
        ];

        $this->assertEquals($expected, $result);
    }
}
