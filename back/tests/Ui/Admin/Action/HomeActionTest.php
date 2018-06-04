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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class HomeActionTest extends TestCase
{
    public function testInvoke()
    {
        $router = $this->prophesize(RouterInterface::class);

        $response = new RedirectResponse("admin_institution_list'");
        $router->generate('admin_institution_list')->shouldBeCalled()->willReturn($response);

        $action = new HomeAction($router->reveal());
        $result = $action();

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }
}
