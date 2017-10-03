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

    /** @var string */
    public $message;

    /**
     * @param string $to phone number
     * @param string $message
     */
    public function __construct($to, $message)
    {
        $this->to      = $to;
        $this->message = $message;
    }
}
