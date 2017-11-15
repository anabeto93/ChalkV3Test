<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Application\Command\Command;
use App\Domain\Model\Session;

class Update extends AbstractSession implements Command
{
    /** @var Session */
    public $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->title = $session->getTitle();
        $this->rank = $session->getRank();
        $this->needValidation = $session->needValidation();
        $this->folder = $session->getFolder();
        $this->enabled = $session->isEnabled();
    }
}
