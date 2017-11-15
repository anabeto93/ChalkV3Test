<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Domain\Proxy;

use Features\Behat\Domain\Storage\StorageInterface;
use Features\Behat\Services\Manager\QuestionManager;

interface QuestionProxyInterface
{
    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface;

    /**
     * @return QuestionManager
     */
    public function getQuestionManager(): QuestionManager;
}
