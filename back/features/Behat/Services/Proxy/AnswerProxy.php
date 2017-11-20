<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Proxy;

use Features\Behat\Domain\Proxy\AnswerProxyInterface;
use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\AnswerManager;

class AnswerProxy implements AnswerProxyInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var AnswerManager */
    private $answerManager;

    /**
     * @param StorageInterface $storage
     * @param AnswerManager    $answerManager
     */
    public function __construct(StorageInterface $storage, AnswerManager $answerManager)
    {
        $this->storage = $storage;
        $this->answerManager = $answerManager;
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
    public function getAnswerManager(): AnswerManager
    {
        return $this->answerManager;
    }
}
