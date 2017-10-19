<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\User\Import;

class UserImportListView
{
    /** @var UserImportView[] */
    public $userImportViews;

    /**
     * @param UserImportView[] $userImportViews
     */
    public function __construct(array $userImportViews)
    {
        $this->userImportViews = $userImportViews;
    }
}
