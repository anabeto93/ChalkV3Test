<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\SessionProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\SessionManager;

class SessionProxy implements SessionProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var SessionManager */
    private $sessionManager;

    /**
     * @param StorageInterface $storage
     * @param SessionManager   $sessionManager
     */
    public function __construct(StorageInterface $storage, SessionManager $sessionManager)
    {
        $this->storage = $storage;
        $this->sessionManager = $sessionManager;
    }
    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }
}
