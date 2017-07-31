<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Adapter;

interface FileStorageInterface
{
    /**
     * @param string $pathFrom
     * @param string $pathTo
     */
    public function copy(string $pathFrom, string $pathTo);

    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * @param string $pathToRemove
     */
    public function remove(string $pathToRemove);

    /**
     * @param string $path
     *
     * @return int
     */
    public function size(string $path): int;
}
