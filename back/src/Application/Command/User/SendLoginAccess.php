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

class SendLoginAccess implements Command
{
    /** @var UserView[] */
    public $userViews;

    /**
     * @param UserView[] $userViews
     */
    public function __construct(array $userViews)
    {
        $this->userViews = $userViews;
    }
}
