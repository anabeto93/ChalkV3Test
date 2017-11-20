<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Session;

class SessionView
{
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var int */
    public $rank;

    /** @var string|null */
    public $folderTitle;

    /** @var bool */
    public $needValidation;

    /** @var int|null */
    public $usersValidated;

    /** @var int */
    public $usersAssignedToCourse;

    /** @var bool */
    public $isEnabled;

    /** @var bool */
    public $hasQuiz;

    /** @var int|null */
    public $usersAnsweredQuizNumber;

    /**
     * @param int         $id
     * @param string      $title
     * @param int         $rank
     * @param string|null $folderTitle
     * @param bool        $needValidation
     * @param bool        $isEnabled
     * @param int|null    $usersValidated
     * @param int         $usersAssignedToCourse
     * @param bool        $hasQuiz
     * @param int|null    $usersAnsweredQuizNumber
     */
    public function __construct(
        int $id,
        string $title,
        int $rank,
        string $folderTitle = null,
        bool $needValidation,
        bool $isEnabled,
        int $usersValidated = null,
        int $usersAssignedToCourse,
        bool $hasQuiz = false,
        int $usersAnsweredQuizNumber = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->rank = $rank;
        $this->folderTitle = $folderTitle;
        $this->needValidation = $needValidation;
        $this->usersValidated = $usersValidated;
        $this->usersAssignedToCourse = $usersAssignedToCourse;
        $this->isEnabled = $isEnabled;
        $this->hasQuiz = $hasQuiz;
        $this->usersAnsweredQuizNumber = $usersAnsweredQuizNumber;
    }
}
