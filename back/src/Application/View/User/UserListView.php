<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\User;

class UserListView
{
    /** @var int */
    public $page;

    /** @var int */
    public $pages;

    /** @var int */
    public $total;

    /** @var array */
    public $users;

    /**
     * @param int $page
     * @param int $pages
     * @param int $total
     */
    public function __construct(int $page, int $pages, int $total)
    {
        $this->page = $page;
        $this->pages = $pages;
        $this->total = $total;
        $this->users = [];
    }

    /**
     * @param UserView $user
     */
    public function addUser(UserView $user)
    {
        $this->users[] = $user;
    }
}
