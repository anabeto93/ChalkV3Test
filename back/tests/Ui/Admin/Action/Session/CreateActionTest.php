<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Session;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Session\Create;
use App\Domain\Exception\Session\Import\ImageFileNotPresentException;
use App\Domain\Exception\Session\Import\ImageWithoutSrcException;
use App\Domain\Exception\Session\Import\IndexFileNotContainInZipException;
use App\Domain\Model\Course;
use App\Ui\Admin\Action\Session\CreateAction;
use App\Ui\Admin\Form\Type\Session\CreateType;
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
            ->create(CreateType::class, $create, ['course' => $course->reveal(), 'submit' => true])
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
        $result = $createAction($request, $course->reveal());

        $this->assertEquals($response, $result);
    }

    public function testInvokeIndexFileNotContainInZipException()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $request = new Request();
        $response = new Response();
        $create = new Create($course->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        // Mock
        $this->engine
            ->renderResponse(CreateAction::TEMPLATE, ['form' => $formView, 'course' => $course->reveal()])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(CreateType::class, $create, ['course' => $course->reveal(), 'submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $this->commandBus->handle($create)->shouldBeCalled()->willThrow(IndexFileNotContainInZipException::class);
        $form->get('content')->shouldBeCalled()->willReturn($form->reveal());
        $this->translator
            ->trans(CreateAction::TRANS_VALIDATOR_INDEX_NOT_PRESENT, [], 'validators')
            ->shouldBeCalled()
            ->willReturn('index file not present in zip')
        ;
        $form->addError(new FormError('index file not present in zip'))->shouldBeCalled();

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request, $course->reveal());

        $this->assertEquals($response, $result);
    }

    public function testInvokeImageWithoutSrcException()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $request = new Request();
        $response = new Response();
        $create = new Create($course->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        // Mock
        $this->engine
            ->renderResponse(CreateAction::TEMPLATE, ['form' => $formView, 'course' => $course->reveal()])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(CreateType::class, $create, ['course' => $course->reveal(), 'submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $this->commandBus->handle($create)->shouldBeCalled()->willThrow(ImageWithoutSrcException::class);
        $form->get('content')->shouldBeCalled()->willReturn($form->reveal());
        $this->translator
            ->trans(CreateAction::TRANS_VALIDATOR_IMAGE_WITHOUT_SRC, [], 'validators')
            ->shouldBeCalled()
            ->willReturn('image without src')
        ;
        $form->addError(new FormError('image without src'))->shouldBeCalled();

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request, $course->reveal());

        $this->assertEquals($response, $result);
    }

    public function testInvokeImageFileNotPresentException()
    {
        $exception = new ImageFileNotPresentException('fileName');

        // Context
        $course = $this->prophesize(Course::class);
        $request = new Request();
        $response = new Response();
        $create = new Create($course->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        // Mock
        $this->engine
            ->renderResponse(CreateAction::TEMPLATE, ['form' => $formView, 'course' => $course->reveal()])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(CreateType::class, $create, ['course' => $course->reveal(), 'submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $this->commandBus->handle($create)->shouldBeCalled()->willThrow($exception);
        $form->get('content')->shouldBeCalled()->willReturn($form->reveal());
        $this->translator
            ->trans(
                CreateAction::TRANS_VALIDATOR_IMAGE_NOT_FOUND,
                ['%image%' => 'fileName'],
                'validators'
            )
            ->shouldBeCalled()
            ->willReturn('image not found')
        ;
        $form->addError(new FormError('image not found'))->shouldBeCalled();

        // Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
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
        $response = new RedirectResponse('/admin/course/uuid-course/session');
        $create = new Create($course->reveal());
        $form = $this->prophesize(FormInterface::class);
        $form->createView()->shouldNotBeCalled();

        // Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory
            ->create(CreateType::class, $create, ['course' => $course->reveal(), 'submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $this->commandBus->handle($create)->shouldBeCalled();
        $this->flashBag->add('success', 'flash.admin.session.create.success')->shouldBeCalled();
        $this->router
            ->generate(CreateAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['course' => 12])
            ->shouldBeCalled()
            ->willReturn('/admin/course/uuid-course/session')
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
        $result = $createAction($request, $course->reveal());

        $this->assertEquals($response, $result);
    }
}
