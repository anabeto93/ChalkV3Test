<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Course;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Course\CourseListQuery;
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
     * @return Response
     */
    public function __invoke(): Response
    {
        $courses = $this->queryBus->handle(new CourseListQuery());

        return $this->engine->renderResponse('Admin/Course/list.html.twig', [
            'courses' => $courses,
        ]);
    }
}
