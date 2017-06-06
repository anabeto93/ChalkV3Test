<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action;

use App\Ui\Admin\Action\HomeAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class HomeActionTest extends TestCase
{
    public function testInvoke()
    {
        $engine = $this->prophesize(EngineInterface::class);

        $response = new Response();
        $engine->renderResponse("Admin/home.html.twig")->shouldBeCalled()->willReturn($response);

        $action = new HomeAction($engine->reveal());
        $result = $action();

        $this->assertEquals($response, $result);
    }
}
