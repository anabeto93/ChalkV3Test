<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

class BatchHandler
{
    /** @var SendLoginAccessHandler */
    private $sendLoginAccessHandler;

    /**
     * @param SendLoginAccessHandler $sendLoginAccessHandler
     */
    public function __construct(SendLoginAccessHandler $sendLoginAccessHandler)
    {
        $this->sendLoginAccessHandler = $sendLoginAccessHandler;
    }

    /**
     * @param Batch $batch
     *
     * @return int number of concerned users
     */
    public function handle(Batch $batch)
    {
        if ($batch->sendLoginAccessAction) {
            return $this->sendLoginAccessHandler->handle(new SendLoginAccess($batch->userViews));
        }

        throw new \InvalidArgumentException('Invalid user batch action');
    }
}
