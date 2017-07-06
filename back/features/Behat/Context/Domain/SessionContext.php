<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Context\Domain;

use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\SessionProxyInterface;

class SessionContext implements Context
{
    /** @var SessionProxyInterface */
    private $sessionProxy;

    /**
     * @param SessionProxyInterface $sessionProxy
     */
    public function __construct(SessionProxyInterface $sessionProxy)
    {
        $this->sessionProxy = $sessionProxy;
    }

    /**
     * @Given /^there is a session with the uuid "(?P<uuid>[^"]+)" and the title "(?P<title>[^"]+)" for this course$/
     *
     * @param string $uuid
     * @param string $title
     */
    public function createSession($uuid, $title)
    {
        $course = $this->sessionProxy->getStorage()->get('course');

        if (!$course instanceof Course) {
            throw new \InvalidArgumentException('Course not found');
        }

        $session = $this->sessionProxy->getSessionManager()->create($uuid, $title, null, $course);
        $this->sessionProxy->getStorage()->set('session', $session);
    }


    /**
     * @Given /^there is a session with the uuid "(?P<uuid>[^"]+)" and the title "(?P<title>[^"]+)" for this course and folder$/
     *
     * @param string $uuid
     * @param string $title
     */
    public function createSessionForFolder($uuid, $title)
    {
        $course = $this->sessionProxy->getStorage()->get('course');
        $folder = $this->sessionProxy->getStorage()->get('folder');

        if (!$course instanceof Course) {
            throw new \InvalidArgumentException('Course not found');
        }

        if (!$folder instanceof Folder) {
            throw new \InvalidArgumentException('Folder not found');
        }

        $session = $this->sessionProxy->getSessionManager()->create($uuid, $title, null, $course, $folder);
        $this->sessionProxy->getStorage()->set('session', $session);
    }
}
