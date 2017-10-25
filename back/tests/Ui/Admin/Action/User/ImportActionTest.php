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
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\User\Import\Import;
use App\Domain\Model\Upload\File;
use App\Ui\Admin\Action\User\ImportAction;
use App\Ui\Admin\Form\Type\User\ImportType;
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
use Symfony\Component\Routing\RouterInterface;

class ImportActionTest extends TestCase
{
    /** @var ObjectProphecy */
    private $engine;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $formFactory;

    /** @var ObjectProphecy */
    private $router;

    /** @var ObjectProphecy */
    private $translator;

    public function setUp()
    {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
    }

    public function testInvoke()
    {
        // Context
        $request = new Request();
        $response = new Response();
        $import = new Import();
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        // Mock
        $this->engine
            ->renderResponse(ImportAction::TEMPLATE, ['form' => $formView])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(ImportType::class, $import, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        // Action
        $importAction = new ImportAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $importAction($request);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle()
    {
        // Context
        $request = new Request();
        $import = new Import();
        $form = $this->prophesize(FormInterface::class);
        $form->createView()->shouldNotBeCalled();
        $file = $this->prophesize(File::class);
        $file->getId()->willReturn(1);

        // Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory
            ->create(ImportType::class, $import, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $this->commandBus->handle($import)->shouldBeCalled()->willReturn($file->reveal());
        $this->router->generate(ImportAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['file' => 1])->shouldBeCalled()->willReturn('/admin/user/import/1/confirm');

        // Action
        $importAction = new ImportAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $importAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEquals('/admin/user/import/1/confirm', $result->getTargetUrl());
    }
}
