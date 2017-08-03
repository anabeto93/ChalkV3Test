<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Adapter;

use App\Application\Command\Command;

interface CommandBusInterface
{
    /**
     * @param Command $command
     *
     * @return mixed
     */
    public function handle(Command $command);
}
