<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\User;

use App\Application\Query\Query;

class UserListQuery implements Query
{
    /** @var int */
    public $page;

    /**
     * @param int $page
     */
    public function __construct(int $page)
    {
        $this->page = $page;
    }
}
