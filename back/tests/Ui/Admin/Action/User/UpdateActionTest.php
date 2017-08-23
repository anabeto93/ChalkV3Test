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
use App\Application\Command\User\Update;
use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Domain\Model\User;
use App\Ui\Admin\Action\User\UpdateAction;
use App\Ui\Admin\Form\Type\User\UpdateType;
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
        $user = new User('uuid', 'firstName', 'lastName', 'phoneNumber', 'FR', 40, new \DateTime());
        $request = new Request();
        $response = new Response();
        $update = new Update($user);
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        // Mock
        $this->engine
            ->renderResponse(UpdateAction::TEMPLATE, ['form' => $formView])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(UpdateType::class, $update, ['submit' => true])
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
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $updateAction($request, $user);

        $this->assertEquals($response, $result);
    }

    public function testInvokeHandleException()
    {
        // Context
        $user = new User('uuid', 'firstName', 'lastName', 'phoneNumber', 'FR', 40, new \DateTime());
        $request = new Request();
        $response = new Response();
        $update = new Update($user);
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->get('phoneNumber')->shouldBeCalled()->willReturn($form->reveal());
        $form->addError(new FormError('Phone number already used'))->shouldBeCalled();

        // Mock
        $this->engine
            ->renderResponse(UpdateAction::TEMPLATE, ['form' => $formView])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(UpdateType::class, $update, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($update)->shouldBeCalled()->willThrow(PhoneNumberAlreadyUsedException::class);
        $this->translator
            ->trans(UpdateAction::TRANS_VALIDATOR_PHONE_NUMBER_USED, [], 'validators')
            ->shouldBeCalled()
            ->willReturn('Phone number already used')
        ;

        // Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $updateAction($request, $user);

        $this->assertEquals($response, $result);
    }

    public function testInvokeHandle()
    {
        // Context
        $user = new User('uuid', 'firstName', 'lastName', 'phoneNumber', 'FR', 40, new \DateTime());
        $request = new Request();
        $response = new RedirectResponse('/admin/user');
        $update = new Update($user);
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
        $this->flashBag->add('success', 'flash.admin.user.update.success')->shouldBeCalled();
        $this->router->generate(UpdateAction::ROUTE_REDIRECT_AFTER_SUCCESS)->shouldBeCalled()->willReturn('/admin/user');

        // Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $updateAction($request, $user);

        $this->assertEquals($response, $result);
    }
}
