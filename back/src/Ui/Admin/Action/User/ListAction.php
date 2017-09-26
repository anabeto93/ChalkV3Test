<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\User;

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
use Symfony\Component\Routing\RouterInterface;

class ListAction
{
    /** @var EngineInterface */
    private $engine;

    /** @var QueryBusInterface */
    private $queryBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /**
     * @param EngineInterface      $engine
     * @param QueryBusInterface    $queryBus
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     */
    public function __construct(
        EngineInterface $engine,
        QueryBusInterface $queryBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->engine      = $engine;
        $this->queryBus    = $queryBus;
        $this->formFactory = $formFactory;
        $this->router      = $router;
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

            return new RedirectResponse($this->router->generate('admin_user_list'));
        }

        return $this->engine->renderResponse('Admin/User/list.html.twig', [
            'userList' => $userList,
            'batchForm' => $batchForm->createView(),
        ]);
    }
}
