<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Session;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Session\SessionListQuery;
use App\Domain\Model\Course;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
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
     * @param Course $course
     *
     * @return Response
     */
    public function __invoke(Course $course): Response
    {
        $sessions = $this->queryBus->handle(new SessionListQuery($course));

        return $this->engine->renderResponse('Admin/Session/list.html.twig', [
            'sessions' => $sessions,
            'course' => $course,
        ]);
    }
}
