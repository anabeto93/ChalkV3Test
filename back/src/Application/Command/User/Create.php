<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Domain\Model\Institution;

class Create extends AbstractUserCommand
{
    /** @var Institution */
    public $institution;

    /**
     * @param Institution $institution
     */
    public function __construct(Institution $institution)
    {
        $this->institution = $institution;
    }
}
