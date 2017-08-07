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
use Behat\Gherkin\Node\TableNode;
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

    /**
     * @Given /^there is a course with the following info$/
     *
     * @param TableNode $nodes
     */
    public function createCourseInfo(TableNode $nodes)
    {
        $sampleCourse = [
            'uuid' => 'sample-uuid',
            'title' => 'Sample title',
            'teacherName' => 'Teacher Name',
            'description' => 'Teacher Name',
            'university' => 'Chalkboard University',
            'enabled' => true,
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime(),
            'size' => 123,
        ];

        foreach ($nodes->getRowsHash() as $node => $text) {
            if ($node === 'createdAt' || $node === 'updatedAt') {
                $text = new \DateTime($text);
            }

            $sampleCourse[$node] = $text;
        }

        $course = $this->courseProxy->getCourseManager()->createWithAllParameters(
            $sampleCourse['uuid'],
            $sampleCourse['title'],
            $sampleCourse['teacherName'],
            $sampleCourse['university'],
            $sampleCourse['enabled'],
            $sampleCourse['description'],
            $sampleCourse['createdAt'],
            $sampleCourse['updatedAt'],
            $sampleCourse['size']
        );

        $this->courseProxy->getStorage()->set('course', $course);
    }
}
