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
use App\Application\Command\User\Create;
use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Ui\Admin\Action\User\CreateAction;
use App\Ui\Admin\Form\Type\User\CreateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

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

    /** @var ObjectProphecy */
    private $translator;

    public function setUp()
    {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
    }

    public function testInvoke()
    {
        // Context
        $request = new Request();
        $response = new Response();
        $create = new Create();
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        // Mock
        $this->engine
            ->renderResponse(CreateAction::TEMPLATE, ['form' => $formView])
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
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandleException()
    {
        // Context
        $request = new Request();
        $response = new Response();
        $create = new Create();
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->get('phoneNumber')->shouldBeCalled()->willReturn($form->reveal());
        $form->addError(new FormError('Phone number already used'))->shouldBeCalled();

        // Mock
        $this->engine
            ->renderResponse(CreateAction::TEMPLATE, ['form' => $formView])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(CreateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($create)->shouldBeCalled()->willThrow(PhoneNumberAlreadyUsedException::class);
        $this->translator
            ->trans(CreateAction::TRANS_VALIDATOR_PHONE_NUMBER_USED, [], 'validators')
            ->shouldBeCalled()
            ->willReturn('Phone number already used')
        ;

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle()
    {
        // Context
        $request = new Request();
        $response = new RedirectResponse('/admin/user');
        $create = new Create();
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
        $this->flashBag->add('success', 'flash.admin.user.create.success')->shouldBeCalled();
        $this->router->generate(CreateAction::ROUTE_REDIRECT_AFTER_SUCCESS)->shouldBeCalled()->willReturn('/admin/user');

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEquals('/admin/user', $result->getTargetUrl());
    }
}
