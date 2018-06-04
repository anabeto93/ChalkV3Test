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
use App\Domain\Model\Institution;
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
     * @param Institution $institution
     *
     * @return Response
     */
    public function __invoke(Institution $institution): Response
    {
        $courses = $this->queryBus->handle(new CourseListQuery($institution));

        return $this->engine->renderResponse('Admin/Course/list.html.twig', [
            'institution' => $institution,
            'courses' => $courses,
        ]);
    }
}
