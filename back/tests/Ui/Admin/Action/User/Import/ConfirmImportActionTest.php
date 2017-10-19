<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\User\Import;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\QueryBusInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\File\Remove;
use App\Application\Command\User\Import\ConfirmImport;
use App\Application\Query\User\Import\ImportQuery;
use App\Application\View\User\Import\UserImportListView;
use App\Domain\Exception\User\Import\InvalidImportHeaderFileFormatException;
use App\Domain\Model\Upload\File;
use App\Ui\Admin\Action\User\ConfirmImportAction;
use App\Ui\Admin\Form\Type\User\ConfirmImportType;
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

class ConfirmImportActionTest extends TestCase
{
    /** @var ObjectProphecy */
    private $engine;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $queryBus;

    /** @var ObjectProphecy */
    private $formFactory;

    /** @var ObjectProphecy */
    private $router;

    /** @var ObjectProphecy */
    private $translator;

    /** @var ObjectProphecy */
    private $flashBag;

    /** @var string */
    private $importDir;

    public function setUp()
    {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->queryBus = $this->prophesize(QueryBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->importDir = '/tmp/';
    }

    public function testInvoke()
    {
        // Context
        $request = new Request();
        $response = new Response();
        $file = $this->prophesize(File::class);
        $confirmImport = new ConfirmImport($file->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        // Mock
        $view = $this->prophesize(UserImportListView::class);
        $this->queryBus->handle(new ImportQuery($file->reveal()))->shouldBeCalled()->willReturn($view);
        $this->engine
            ->renderResponse(ConfirmImportAction::TEMPLATE, [
                'form' => $formView,
                 'userImportListView' => $view,
            ])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this->formFactory
            ->create(ConfirmImportType::class, $confirmImport)
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);


        // Action
        $confirmImportAction = new ConfirmImportAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->queryBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal(),
            $this->importDir
        );
        $result = $confirmImportAction($request, $file->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle()
    {
        // Context
        $request = new Request();
        $file = $this->prophesize(File::class);
        $confirmImport = new ConfirmImport($file->reveal());
        $form = $this->prophesize(FormInterface::class);
        $form->createView()->shouldNotBeCalled();

        // Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory
            ->create(ConfirmImportType::class, $confirmImport)
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $this->commandBus->handle($confirmImport)->shouldBeCalled()->willReturn($file->reveal());
        $this->router->generate(ConfirmImportAction::ROUTE_REDIRECT_AFTER_SUCCESS)->shouldBeCalled()->willReturn('/admin/user');

        // Action
        $confirmImportAction = new ConfirmImportAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->queryBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal(),
            $this->importDir
        );
        $result = $confirmImportAction($request, $file->reveal());

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEquals('/admin/user', $result->getTargetUrl());
    }

    public function testInvokeHandleException()
    {
        // Context
        $request = new Request();
        $file = $this->prophesize(File::class);
        $confirmImport = new ConfirmImport($file->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldNotBeCalled();

        // Mock
        $view = $this->prophesize(UserImportListView::class);
        $this->queryBus->handle(new ImportQuery($file->reveal()))->willThrow(InvalidImportHeaderFileFormatException::class);
        $this->engine
            ->renderResponse(ConfirmImportAction::TEMPLATE, [
                'form' => $formView,
                'userImportListView' => $view,
            ])
            ->shouldNotBeCalled()
        ;
        $this->formFactory
            ->create(ConfirmImportType::class, $confirmImport)
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;
        $this->router->generate(ConfirmImportAction::ROUTE_REDIRECT_ERROR)->shouldBeCalled()->willReturn('/admin/user/import');
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);
        $this->flashBag->add('error', 'flash.admin.user.import.errorHeader')->shouldBeCalled();
        $this->commandBus->handle(new Remove($file->reveal(), $this->importDir))->shouldBeCalled();

        // Action
        $confirmImportAction = new ConfirmImportAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->queryBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal(),
            $this->importDir
        );
        $result = $confirmImportAction($request, $file->reveal());

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }
}
