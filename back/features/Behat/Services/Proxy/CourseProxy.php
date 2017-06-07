<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\CourseProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\CourseManager;

class CourseProxy implements CourseProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var CourseManager */
    private $courseManager;

    /**
     * @param StorageInterface $storage
     * @param CourseManager    $courseManager
     */
    public function __construct(StorageInterface $storage, CourseManager $courseManager)
    {
        $this->storage = $storage;
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
     * @return CourseManager
     */
    public function getCourseManager(): CourseManager
    {
        return $this->courseManager;
    }
}
