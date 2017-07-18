<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;

class SessionManager
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /**
     * @param SessionRepositoryInterface $sessionRepository
     */
    public function __construct(SessionRepositoryInterface $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @param string      $uuid
     * @param string      $sessionTitle
     * @param string|null $content
     * @param Course      $course
     * @param Folder|null $folder
     *
     * @return Session
     */
    public function create(
        string $uuid,
        string $sessionTitle,
        string $content = null,
        Course $course,
        Folder $folder = null
    ): Session {
        $session = new Session($uuid, $sessionTitle, $content, $course, $folder, new \DateTime());

        $this->sessionRepository->add($session);

        return $session;
    }
}
