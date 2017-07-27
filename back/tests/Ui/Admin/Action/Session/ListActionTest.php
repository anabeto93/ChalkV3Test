<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Session;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Session\SessionListQuery;
use App\Application\View\Session\SessionView;
use App\Domain\Model\Course;
use App\Ui\Admin\Action\Session\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $session1 = $this->prophesize(SessionView::class);
        $session2 = $this->prophesize(SessionView::class);
        $sessions = [$session1->reveal(), $session2->reveal()];

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new SessionListQuery($course->reveal()))->shouldBeCalled()->willReturn($sessions)
        ;
        $response = new Response();
        $engine
            ->renderResponse("Admin/Session/list.html.twig", [
                'course' => $course->reveal(),
                'sessions' => $sessions
            ])->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action($course->reveal());

        $this->assertEquals($response, $result);
    }
}
