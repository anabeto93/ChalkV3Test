<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class CommonContext extends MinkContext implements Context
{
    /**
     * @Given /^I dump the page$/
     */
    public function dumpPage()
    {
        echo $this->getSession()->getPage()->getOuterHtml();
    }
}
