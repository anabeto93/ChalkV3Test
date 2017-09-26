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
use infobip\api\configuration\ApiKeyAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class SMSSender implements SMSSenderInterface
{
    /** @var string */
    private $infoBipApiKey;

    /**
     * @param string $infoBipApiKey
     */
    public function __construct(string $infoBipApiKey)
    {
        $this->infoBipApiKey = $infoBipApiKey;
    }

    /**
     * @param SMSView $sms
     */
    public function send(SMSView $sms)
    {
        $configuration = new ApiKeyAuthConfiguration($this->infoBipApiKey);
        $client        = new SendSingleTextualSms($configuration);

        $requestBody = new SMSTextualRequest();
        $requestBody->setFrom($sms->getFrom());
        $requestBody->setTo($sms->getTo());
        $requestBody->setText($sms->getMessage());

        $client->execute($requestBody);
    }
}
