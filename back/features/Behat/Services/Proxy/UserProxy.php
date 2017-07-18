<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\UserProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\UserManager;

class UserProxy implements UserProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var UserManager */
    private $userManager;

    /**
     * @param StorageInterface $storage
     * @param UserManager      $userManager
     */
    public function __construct(StorageInterface $storage, UserManager $userManager)
    {
        $this->storage = $storage;
        $this->userManager = $userManager;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @return UserManager
     */
    public function getUserManager(): UserManager
    {
        return $this->userManager;
    }
}
