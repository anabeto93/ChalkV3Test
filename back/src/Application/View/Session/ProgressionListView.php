<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Session;

use App\Application\View\Session\Progression\UserValidatedView;
use App\Application\View\Session\Progression\UserView;

class ProgressionListView
{
    /** @var UserValidatedView[] */
    public $usersValidated;

    /** @var UserView[] */
    public $usersNotValidated;

    public function __construct()
    {
        $this->usersValidated = [];
        $this->usersNotValidated = [];
    }

    /**
     * @param UserValidatedView $userValidatedView
     */
    public function addUserValidated(UserValidatedView $userValidatedView)
    {
        $this->usersValidated[] = $userValidatedView;
    }

    /**
     * @param UserView $userView
     */
    public function addUserNotValidated(UserView $userView)
    {
        $this->usersNotValidated[] = $userView;
    }

    /**
     * Sort the usersValidated and usersNotValidated array by user lastName
     */
    public function sortUserByLastName()
    {
        $strcmpLastName = function (UserView $userView, UserView $otherUserView) {
            return strcmp($userView->lastName, $otherUserView->lastName);
        };

        usort($this->usersValidated, $strcmpLastName);

        usort($this->usersNotValidated, $strcmpLastName);
    }
}
