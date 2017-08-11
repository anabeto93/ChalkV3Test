<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Session;


use App\Domain\Model\Course;
use App\Domain\Model\Session;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PreviewAction
{
    /** @var EngineInterface */
    private $engine;

    /**
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
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

        return $this->engine->renderResponse('Admin/Session/preview.html.twig', [
            'course' => $course,
            'session' => $session
        ]);
    }
}
