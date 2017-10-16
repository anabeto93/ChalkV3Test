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
use App\Application\Query\Session\ProgressionListQuery;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProgressionAction
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
     * @param Course  $course
     * @param Session $session
     *
     * @return Response
     */
    public function __invoke(Course $course, Session $session): Response
    {
        if ($course !== $session->getCourse()) {
            throw new NotFoundHttpException('This session is not in this course');
        }

        if (!$session->needValidation()) {
            throw new NotFoundHttpException('This session does not need validation');
        }

        $progressionList = $this->queryBus->handle(new ProgressionListQuery($session));

        return $this->engine->renderResponse('Admin/Session/progression.html.twig', [
            'course'          => $course,
            'progressionList' => $progressionList,
            'session'         => $session,
        ]);
    }
}
