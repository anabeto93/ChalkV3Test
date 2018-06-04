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
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Course\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $institution = $this->prophesize(Institution::class);
        $course1 = new CourseView(1, 'title1', 'teacherName1', true, 1, 1, 0);
        $course2 = new CourseView(2, 'title2', 'teacherName2', false, 4, 8, 0);

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new CourseListQuery($institution->reveal()))->shouldBeCalled()->willReturn([$course1, $course2]);
        $response = new Response();
        $engine
            ->renderResponse("Admin/Course/list.html.twig", ['institution' => $institution->reveal(),'courses' => [$course1, $course2]])
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action($institution->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }
}
