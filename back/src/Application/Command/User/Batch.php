<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Application\Command\Command;
use App\Application\View\User\UserView;

class Batch implements Command
{
    /** @var UserView[] */
    public $userViews;

    /** @var bool */
    public $sendLoginAccessAction;
}
