<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Folder;

use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Folder\FolderListQuery;
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
        $folders = $this->queryBus->handle(new FolderListQuery($course));

        return $this->engine->renderResponse('Admin/Folder/list.html.twig', [
            'folders' => $folders,
            'course' => $course,
        ]);
    }
}
