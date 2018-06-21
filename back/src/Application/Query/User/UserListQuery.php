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
use App\Domain\Model\Institution;

class UserListQuery implements Query
{
    /** @var Institution */
    public $institution;

    /** @var int */
    public $page;

    /**
     * @param Institution $institution
     * @param int $page
     */
    public function __construct(Institution $institution,int $page)
    {
        $this->institution = $institution;
        $this->page = $page;
    }
}
