<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Adapter;

use App\Application\View\SMS\SMSView;

interface SMSSenderInterface
{
    /**
     * @param SMSView $sms
     */
    public function send(SMSView $sms);
}
