<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\SMS;

class SMSView
{
    /** @var string */
    private $from;

    /** @var array */
    private $to;

    /** @var string */
    private $message;

    /**
     * @param string $from
     * @param array  $to
     * @param string $message
     */
    public function __construct(string $from, array $to, string $message)
    {
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
