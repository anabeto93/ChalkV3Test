<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Session;

use App\Application\View\Session\SessionView;
use App\Domain\Repository\SessionRepositoryInterface;

class SessionListQueryHandler
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
     * @param SessionListQuery $query
     *
     * @return SessionView[]
     */
    public function handle(SessionListQuery $query): array
    {
        $sessionViews = [];
        $sessions = $this->sessionRepository->findByCourse($query->course);

        foreach ($sessions as $session) {
            $sessionViews[] = new SessionView(
                $session->getId(),
                $session->getTitle(),
                $session->getRank(),
                $session->hasFolder() ? $session->getFolder()->getTitle() : null
            );
        }

        return $sessionViews;
    }
}
