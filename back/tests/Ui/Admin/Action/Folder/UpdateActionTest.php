<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Folder;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Folder\Update;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Ui\Admin\Action\Folder\UpdateAction;
use App\Ui\Admin\Form\Type\Folder\UpdateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class UpdateActionTest extends TestCase
{
    /** @var ObjectProphecy */
    private $engine;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $formFactory;

    /** @var ObjectProphecy */
    private $flashBag;

    /** @var ObjectProphecy */
    private $router;

    public function setUp()
    {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
    }

    public function testInvoke()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $folder = new Folder('uuid', 0, 'title', $course->reveal(), new \DateTime());
        $request = new Request();
        $response = new Response();
        $create = new Update($folder);
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        // Mock
        $this->engine
            ->renderResponse(UpdateAction::TEMPLATE, ['form' => $formView, 'course' => $course->reveal()])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(UpdateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        // Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );
        $result = $updateAction($request, $course->reveal(), $folder);

        $this->assertInstanceOf(Response::class, $result);
    }


    public function testInvokeHandle()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $folder = new Folder('uuid', 0, 'title', $course->reveal(), new \DateTime());
        $course->getId()->shouldBeCalled()->willReturn(12);
        $request = new Request();
        $response = new RedirectResponse('/admin/course/uuid-course/folder');
        $update = new Update($folder);
        $form = $this->prophesize(FormInterface::class);
        $form->createView()->shouldNotBeCalled();

        // Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory
            ->create(UpdateType::class, $update, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $this->commandBus->handle($update)->shouldBeCalled();
        $this->flashBag->add('success', 'flash.admin.folder.update.success')->shouldBeCalled();
        $this->router
            ->generate(UpdateAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['course' => 12])
            ->shouldBeCalled()
            ->willReturn('/admin/course/uuid-course/folder')
        ;

        // Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );
        $result = $updateAction($request, $course->reveal(), $folder);

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEquals('/admin/course/uuid-course/folder', $result->getTargetUrl());
    }
}
