<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Session;

use App\Application\Query\Query;
use App\Domain\Model\Session;

class ProgressionListQuery implements Query
{
    /** @var Session */
    public $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
}
