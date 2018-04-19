<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Course;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Course\CourseListQuery;
use App\Application\View\Course\CourseView;
use App\Ui\Admin\Action\Course\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $course1 = new CourseView(1, 'title1', 'teacherName1', 'university1', true);
        $course2 = new CourseView(2, 'title2', 'teacherName2', 'university2', false);

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new CourseListquery())->shouldBeCalled()->willReturn([$course1, $course2]);
        $response = new Response();
        $engine
            ->renderResponse("Admin/Course/list.html.twig", ['courses' => [$course1, $course2]])
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action();

        $this->assertInstanceOf(Response::class, $result);
    }
}
