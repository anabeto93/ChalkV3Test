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
use App\Application\Query\Session\ProgressionListQuery;
use App\Application\View\Session\ProgressionListView;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Ui\Admin\Action\Session\ProgressionAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ProgressionActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $session = $this->prophesize(Session::class);

        $session->getCourse()->shouldBeCalled()->willReturn($course->reveal());
        $session->needValidation()->shouldBeCalled()->willReturn(true);
        $progressionList = new ProgressionListView();

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new ProgressionListQuery($session->reveal()))->shouldBeCalled()->willReturn($progressionList);
        $response = new Response();
        $engine
            ->renderResponse("Admin/Session/progression.html.twig", [
                'course' => $course->reveal(),
                'session' => $session->reveal(),
                'progressionList' => $progressionList,
            ])->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new ProgressionAction($engine->reveal(), $queryBus->reveal());
        $result = $action($course->reveal(), $session->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }
}
