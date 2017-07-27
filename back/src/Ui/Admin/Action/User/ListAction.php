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
use App\Application\Query\User\UserListQuery;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListAction
{
    /** @var EngineInterface */
    private $engine;

    /** @var QueryBusInterface */
    private $queryBus;

    /**
     * @param EngineInterface   $engine
     * @param QueryBusInterface $queryBus
     */
    public function __construct(EngineInterface $engine, QueryBusInterface $queryBus)
    {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $userList = $this->queryBus->handle(new UserListQuery($request->get('page', 1)));

        return $this->engine->renderResponse('Admin/User/list.html.twig', [
            'userList' => $userList,
        ]);
    }
}
