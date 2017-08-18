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
use App\Domain\Model\User;
use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\UserProxyInterface;

class UserContext implements Context
{
    /** @var UserProxyInterface */
    private $userProxy;

    /**
     * @param UserProxyInterface $userProxy
     */
    public function __construct(UserProxyInterface $userProxy)
    {
        $this->userProxy = $userProxy;
    }

    /**
     * @Given /^there is a user called "(?P<firstName>[^"]+)" "(?P<lastName>[^"]+)" with the uuid "(?P<uuid>[^"]+)" and the phone number "(?P<phoneNumber>[^"]+)"$/
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $uuid
     * @param string $phoneNumber
     */
    public function createUser(string $firstName, string $lastName, string $uuid, string $phoneNumber)
    {
        $user = $this->userProxy->getUserManager()->create($uuid, $firstName, $lastName, $phoneNumber);

        $this->userProxy->getStorage()->set('user', $user);
    }

    /**
     * @Given /^the api token for this user is "(?P<apiToken>[^"]+)"$/
     *
     * @param string $apiToken
     */
    public function setApiTokenForUser(string $apiToken)
    {
        $user = $this->userProxy->getStorage()->get('user');

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User not found');
        }

        $this->userProxy->getUserManager()->setApiToken($user, $apiToken);
    }

    /**
     * @Given this user is assigned to this course
     */
    public function setCourseAssignedForUser()
    {
        $user = $this->userProxy->getStorage()->get('user');
        $course = $this->userProxy->getStorage()->get('course');

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User not found');
        }

        if (!$course instanceof Course) {
            throw new \InvalidArgumentException('Course not found');
        }

        $this->userProxy->getUserManager()->setCourse($user, $course);
    }
}
