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
use Behat\Gherkin\Node\TableNode;
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
     * @Given /^there is following users$/
     *
     * @param TableNode $users
     */
    public function createUser(TableNode $users)
    {
        foreach ($users->getHash() as $userHash) {
            $user = $this->userProxy->getUserManager()->create(
                $userHash['uuid'],
                $userHash['firstName'],
                $userHash['lastName'],
                $userHash['phoneNumber'],
                $userHash['locale'],
                $userHash['multiLogin'],
                $userHash['country'] ?? null,
                $userHash['token'] ?? null
            );

            $this->userProxy->getStorage()->set('user', $user);
        }
    }

    /**
     * @Given /^there is a user with force update$/
     */
    public function createUserWithForceUpdate()
    {
        $user = $this->userProxy->getUserManager()->create(
            1,
            'john',
            'doh',
            '+33123213123',
            'fr',
            true,
            'fr',
            'token2',
            true
        );

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
     * @Given /^this user is assigned to this course on "(?P<dateTime>[^"]+)"$/
     *
     * @param string $dateTime
     */
    public function thisUserIsAssignedToThisCourseOnDateTime(string $dateTime)
    {
        $this->setCourseAssignedForUser(new \DateTime($dateTime));
    }

    /**
     * @Given this user is assigned to this course
     */
    public function thisUserIsAssignedToThisCourse()
    {
        $this->setCourseAssignedForUser();
    }

    /**
     * @param \DateTimeInterface|null $dateTime
     */
    private function setCourseAssignedForUser(?\DateTimeInterface $dateTime = null)
    {
        $user = $this->userProxy->getStorage()->get('user');
        $course = $this->userProxy->getStorage()->get('course');

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User not found');
        }

        if (!$course instanceof Course) {
            throw new \InvalidArgumentException('Course not found');
        }

        $this->userProxy->getCourseManager()->addCourseToUser($user, $course, $dateTime);
    }
}
