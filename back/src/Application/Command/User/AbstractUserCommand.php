<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Application\Command\Command;

abstract class AbstractUserCommand implements Command
{
    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string */
    public $phoneNumber;

    /** @var string */
    public $country;

    /** @var string */
    public $locale;
}
