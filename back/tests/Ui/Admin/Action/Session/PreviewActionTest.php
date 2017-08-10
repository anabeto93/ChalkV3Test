<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Session;

use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Ui\Admin\Action\Session\PreviewAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class PreviewActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $session = $this->prophesize(Session::class);

        $session->getCourse()->shouldBeCalled()->willReturn($course->reveal());

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $response = new Response();
        $engine
            ->renderResponse("Admin/Session/preview.html.twig", [
                'course' => $course->reveal(),
                'session' => $session->reveal()
            ])->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new PreviewAction($engine->reveal());
        $result = $action($course->reveal(), $session->reveal());

        $this->assertEquals($response, $result);
    }
}
