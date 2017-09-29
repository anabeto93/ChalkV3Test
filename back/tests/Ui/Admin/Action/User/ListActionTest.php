<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\User;

use App\Application\Adapter\CommandBusInterface;
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
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
        $commandBus = $this->prophesize(CommandBusInterface::class);
        $flashBag = $this->prophesize(FlashBagInterface::class);
        $translator = $this->prophesize(TranslatorInterface::class);

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

        $action = new ListAction(
            $commandBus->reveal(),
            $engine->reveal(),
            $flashBag->reveal(),
            $formFactory->reveal(),
            $queryBus->reveal(),
            $router->reveal(),
            $translator->reveal()
        );
        $result = $action($request);

        $this->assertInstanceOf(Response::class, $result);
    }
}
