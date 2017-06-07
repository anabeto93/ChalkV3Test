<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Storage;

use Features\Behat\Domain\Storage\StorageInterface;

class Storage implements StorageInterface
{
    /**
     * @var array
     */
    private $storage;

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->storage[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return isset($this->storage[$name]) ? $this->storage[$name] : null;
    }
}
