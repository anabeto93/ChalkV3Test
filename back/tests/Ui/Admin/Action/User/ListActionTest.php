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
use App\Application\Command\User\Batch;
use App\Application\Query\User\UserListQuery;
use App\Application\View\User\UserListView;
use App\Ui\Admin\Action\User\ListAction;
use App\Ui\Admin\Form\Type\User\BatchType;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

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
        $formFactory = $this->prophesize(FormFactoryInterface::class);
        $router = $this->prophesize(RouterInterface::class);
        $batchForm = $this->prophesize(FormInterface::class);
        $batchFormView = $this->prophesize(FormView::class);
        $batchForm->createView()->shouldBeCalled()->willReturn($batchFormView->reveal());

        $queryBus->handle(new UserListQuery(1))->shouldBeCalled()->willReturn($userList->reveal());
        $response = new Response();
        $engine
            ->renderResponse(
                'Admin/User/list.html.twig',
                [
                    'userList' => $userList->reveal(),
                    'batchForm' => $batchFormView->reveal(),
                ]
            )
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $batch = new Batch();

        $formFactory
            ->create(BatchType::class, $batch, ['userViews' => []])
            ->shouldBeCalled()
            ->willReturn($batchForm->reveal())
        ;

        $batchForm->handleRequest($request)->shouldBeCalled()->willReturn($batchForm->reveal());
        $batchForm->isSubmitted()->shouldBeCalled()->willReturn(false);

        $action = new ListAction($engine->reveal(), $queryBus->reveal(), $formFactory->reveal(), $router->reveal());
        $result = $action($request);

        $this->assertInstanceOf(Response::class, $result);
    }
}
