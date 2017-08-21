<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Progression;

use App\Application\Command\Command;
use App\Domain\Model\User;

class ValidateSession implements Command
{
    /** @var User */
    public $user;

    /** @var string */
    public $sessionUuid;

    /**
     * @param User   $user
     * @param string $sessionUuid
     */
    public function __construct(User $user, string $sessionUuid)
    {
        $this->user = $user;
        $this->sessionUuid = $sessionUuid;
    }
}
