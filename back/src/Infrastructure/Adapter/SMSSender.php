<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Adapter;

use App\Application\Adapter\SMSSenderInterface;
use App\Application\View\SMS\SMSView;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class SMSSender implements SMSSenderInterface
{
    /** @var string */
    private $infoBipUsername;

    /** @var string */
    private $infoBipPassword;

    /**
     * @param string $infoBipUsername
     * @param string $infoBipPassword
     */
    public function __construct(string $infoBipUsername, string $infoBipPassword)
    {
        $this->infoBipUsername = $infoBipUsername;
        $this->infoBipPassword = $infoBipPassword;
    }

    /**
     * @param SMSView $sms
     */
    public function send(SMSView $sms)
    {
        $configuration = new BasicAuthConfiguration($this->infoBipUsername, $this->infoBipPassword);
        $client        = new SendSingleTextualSms($configuration);

        $requestBody = new SMSTextualRequest();
        $requestBody->setFrom($sms->getFrom());
        $requestBody->setTo($sms->getTo());
        $requestBody->setText($sms->getMessage());

        $client->execute($requestBody);
    }
}
