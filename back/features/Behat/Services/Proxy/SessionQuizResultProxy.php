<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\SessionQuizResultProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\SessionQuizResultManager;

class SessionQuizResultProxy implements SessionQuizResultProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var SessionQuizResultManager */
    private $sessionQuizResultManager;

    /**
     * @param StorageInterface         $storage
     * @param SessionQuizResultManager $sessionQuizResultManager
     */
    public function __construct(StorageInterface $storage, SessionQuizResultManager $sessionQuizResultManager)
    {
        $this->storage = $storage;
        $this->sessionQuizResultManager = $sessionQuizResultManager;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @return SessionQuizResultManager
     */
    public function getSessionQuizResultManager(): SessionQuizResultManager
    {
        return $this->sessionQuizResultManager;
    }
}
