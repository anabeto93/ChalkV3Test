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
use App\Application\Command\Folder\Create;
use App\Domain\Model\Course;
use App\Ui\Admin\Action\Folder\CreateAction;
use App\Ui\Admin\Form\Type\Folder\CreateType;
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

class CreateActionTest extends TestCase
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
        $request = new Request();
        $response = new Response();
        $create = new Create($course->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        // Mock
        $this->engine
            ->renderResponse(CreateAction::TEMPLATE, ['form' => $formView, 'course' => $course->reveal()])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(CreateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );
        $result = $createAction($request, $course->reveal());

        $this->assertEquals($response, $result);
    }


    public function testInvokeHandle()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $course->getId()->shouldBeCalled()->willReturn(12);
        $request = new Request();
        $response = new RedirectResponse('/admin/course/uuid-course/folder');
        $create = new Create($course->reveal());
        $form = $this->prophesize(FormInterface::class);
        $form->createView()->shouldNotBeCalled();

        // Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory
            ->create(CreateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $this->commandBus->handle($create)->shouldBeCalled();
        $this->flashBag->add('success', 'flash.admin.folder.create.success')->shouldBeCalled();
        $this->router
            ->generate(CreateAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['course' => 12])
            ->shouldBeCalled()
            ->willReturn('/admin/course/uuid-course/folder')
        ;

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );
        $result = $createAction($request, $course->reveal());

        $this->assertEquals($response, $result);
    }
}
