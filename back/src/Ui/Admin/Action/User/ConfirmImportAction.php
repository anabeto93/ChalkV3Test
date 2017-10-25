<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\User;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\QueryBusInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\File\Remove;
use App\Application\Command\User\Import\ConfirmImport;
use App\Application\Query\User\Import\ImportQuery;
use App\Domain\Exception\User\Import\InvalidImportHeaderFileFormatException;
use App\Domain\Model\Upload\File;
use App\Ui\Admin\Form\Type\User\ConfirmImportType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class ConfirmImportAction
{
    const TEMPLATE = 'Admin/User/confirmImport.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_user_list';
    const ROUTE_REDIRECT_ERROR = 'admin_user_import';

    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var RouterInterface */
    private $router;

    /** @var TranslatorInterface */
    private $translator;

    /** @var QueryBusInterface */
    private $queryBus;

    /** @var string */
    private $importDir;

    /**
     * @param EngineInterface      $engine
     * @param CommandBusInterface  $commandBus
     * @param QueryBusInterface    $queryBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface    $flashBag
     * @param RouterInterface      $router
     * @param TranslatorInterface  $translator
     * @param string               $importDir
     */
    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        TranslatorInterface $translator,
        string $importDir
    ) {
        $this->engine      = $engine;
        $this->commandBus  = $commandBus;
        $this->queryBus    = $queryBus;
        $this->formFactory = $formFactory;
        $this->flashBag    = $flashBag;
        $this->router      = $router;
        $this->translator  = $translator;
        $this->importDir   = $importDir;
    }

    /**
     * @param Request $request
     * @param File    $file
     *
     * @return Response
     */
    public function __invoke(Request $request, File $file): Response
    {
        $import = new ConfirmImport($file);
        $form = $this->formFactory->create(ConfirmImportType::class, $import);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($import);
            $this->flashBag->add('success', 'flash.admin.user.import.success');

            return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS));
        }

        try {
            $userImportListView = $this->queryBus->handle(new ImportQuery($file));
        } catch (InvalidImportHeaderFileFormatException $exception) {
            $this->flashBag->add('error', 'flash.admin.user.import.errorHeader');
            $this->commandBus->handle(new Remove($file, $this->importDir));

            return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_ERROR));
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'form'               => $form->createView(),
            'userImportListView' => $userImportListView,
        ]);
    }
}
