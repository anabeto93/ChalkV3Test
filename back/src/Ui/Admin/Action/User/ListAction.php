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
use App\Application\Command\User\Batch;
use App\Application\Query\User\UserListQuery;
use App\Application\View\User\UserListView;
use App\Ui\Admin\Form\Type\User\BatchType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ListAction
{
    /** @var CommandBusInterface */
    private $commandBus;

    /** @var EngineInterface */
    private $engine;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var QueryBusInterface */
    private $queryBus;

    /** @var RouterInterface */
    private $router;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * @param CommandBusInterface  $commandBus
     * @param EngineInterface      $engine
     * @param FlashBagInterface    $flashBag
     * @param FormFactoryInterface $formFactory
     * @param QueryBusInterface    $queryBus
     * @param RouterInterface      $router
     * @param TranslatorInterface  $translator
     */
    public function __construct(
        CommandBusInterface $commandBus,
        EngineInterface $engine,
        FlashBagInterface $flashBag,
        FormFactoryInterface $formFactory,
        QueryBusInterface $queryBus,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->commandBus  = $commandBus;
        $this->engine      = $engine;
        $this->flashBag    = $flashBag;
        $this->formFactory = $formFactory;
        $this->queryBus    = $queryBus;
        $this->router      = $router;
        $this->translator  = $translator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        /** @var UserListView $userList */
        $userList = $this->queryBus->handle(new UserListQuery($request->get('page', 1)));

        $batch = new Batch();

        $batchForm = $this->formFactory->create(
            BatchType::class,
            $batch,
            ['userViews' => $userList->users]
        );

        if ($batchForm->handleRequest($request)->isSubmitted() && $batchForm->isValid()) {
            $batch->sendLoginAccessAction = $batchForm->get('sendLoginAccessAction')->isClicked();

            if ($batch->sendLoginAccessAction) {
                $countUsersNotified = $this->commandBus->handle($batch);

                $this->flashBag->add(
                    $countUsersNotified > 0 ? 'success' : 'warning',
                    $this->translator->transChoice(
                        'flash.admin.user.batch.sendLoginAccessAction.success',
                        $countUsersNotified,
                        ['%countUsersNotified%' => $countUsersNotified],
                        'flashes'
                    )
                );
            }

            return new RedirectResponse($this->router->generate('admin_user_list'));
        }

        return $this->engine->renderResponse('Admin/User/list.html.twig', [
            'userList' => $userList,
            'batchForm' => $batchForm->createView(),
        ]);
    }
}
