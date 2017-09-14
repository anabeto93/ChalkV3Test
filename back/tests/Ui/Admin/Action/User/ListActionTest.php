<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\User;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\User\UserListQuery;
use App\Application\View\User\UserListView;
use App\Ui\Admin\Action\User\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase
{
    public function testInvoke()
    {
        // Context
        $request = new Request(['page' => 1]);
        $userList = $this->prophesize(UserListView::class);

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new UserListQuery(1))->shouldBeCalled()->willReturn($userList->reveal());
        $response = new Response();
        $engine
            ->renderResponse("Admin/User/list.html.twig", ['userList' => $userList->reveal()])
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action($request);

        $this->assertInstanceOf(Response::class, $result);
    }
}
