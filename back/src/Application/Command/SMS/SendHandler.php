<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\SMS;

use App\Application\Adapter\SMSSenderInterface;
use App\Application\View\SMS\SMSView;

class SendHandler
{
    /** @var SMSSenderInterface */
    private $SMSSender;

    /** @var string */
    private $fromNumber;

    /**
     * @param SMSSenderInterface $SMSSender
     * @param string             $fromNumber
     */
    public function __construct(SMSSenderInterface $SMSSender, string $fromNumber)
    {
        $this->SMSSender = $SMSSender;
        $this->fromNumber = $fromNumber;
    }

    /**
     * @param Send $command
     */
    public function handle(Send $command)
    {
        $sms = new SMSView($this->fromNumber, [$command->to], $command->message);

        $this->SMSSender->send($sms);
    }
}
