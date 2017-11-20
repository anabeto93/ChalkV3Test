<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\QuestionProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\QuestionManager;

class QuestionProxy implements QuestionProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var QuestionManager */
    private $questionManager ;

    /**
     * @param StorageInterface $storage
     * @param QuestionManager  $questionManager
     */
    public function __construct(StorageInterface $storage, QuestionManager $questionManager)
    {
        $this->storage = $storage;
        $this->questionManager = $questionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionManager(): QuestionManager
    {
        return $this->questionManager;
    }
}
