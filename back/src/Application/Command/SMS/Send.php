<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\SMS;

use App\Application\Command\Command;

class Send implements Command
{
    /** @var string */
    public $to;
}
