<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Context\Domain;

use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\CourseProxyInterface;

class CourseContext implements Context
{
    /** @var CourseProxyInterface */
    private $courseProxy;

    /**
     * @param CourseProxyInterface $courseProxy
     */
    public function __construct(CourseProxyInterface $courseProxy)
    {
        $this->courseProxy = $courseProxy;
    }

    /**
     * @Given /^there is a course with the uuid "(?P<uuid>[^"]+)" and the title "(?P<title>[^"]+)"$/
     *
     * @param string $uuid
     * @param string $title
     */
    public function createCourse($uuid, $title)
    {
        $course = $this->courseProxy->getCourseManager()->create($uuid, $title);
        $this->courseProxy->getStorage()->set('course', $course);
    }
}
