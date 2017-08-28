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
use Features\Behat\Services\Manager\CourseManager;
use Features\Behat\Services\Manager\UserManager;

class UserProxy implements UserProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var UserManager */
    private $userManager;

    /** @var CourseManager */
    private $courseManager;

    /**
     * @param StorageInterface $storage
     * @param UserManager      $userManager
     * @param CourseManager    $courseManager
     */
    public function __construct(StorageInterface $storage, UserManager $userManager, CourseManager $courseManager)
    {
        $this->storage = $storage;
        $this->userManager = $userManager;
        $this->courseManager = $courseManager;
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

    /**
     * @return CourseManager
     */
    public function getCourseManager(): CourseManager
    {
        return $this->courseManager;
    }
}
