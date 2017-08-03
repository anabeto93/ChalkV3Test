<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Folder;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Folder\FolderListQuery;
use App\Application\View\Folder\FolderView;
use App\Domain\Model\Course;
use App\Ui\Admin\Action\Folder\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $folder1 = $this->prophesize(FolderView::class);
        $folder2 = $this->prophesize(FolderView::class);
        $folders = [$folder1->reveal(), $folder2->reveal()];

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new FolderListQuery($course->reveal()))->shouldBeCalled()->willReturn($folders);
        $response = new Response();
        $engine
            ->renderResponse("Admin/Folder/list.html.twig", ['course' => $course->reveal(), 'folders' => $folders])
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action($course->reveal());

        $this->assertEquals($response, $result);
    }
}
