<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\FolderProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\FolderManager;

class FolderProxy implements FolderProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var FolderManager */
    private $folderManager;

    /**
     * @param StorageInterface $storage
     * @param FolderManager    $folderManager
     */
    public function __construct(StorageInterface $storage, FolderManager $folderManager)
    {
        $this->storage = $storage;
        $this->folderManager = $folderManager;
    }
    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @return FolderManager
     */
    public function getFolderManager(): FolderManager
    {
        return $this->folderManager;
    }
}
