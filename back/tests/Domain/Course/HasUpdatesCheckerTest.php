<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Course;

use App\Domain\Course\CourseUpdateView;
use App\Domain\Course\FolderUpdateView;
use App\Domain\Course\HasUpdatesChecker;
use App\Domain\Course\SessionUpdateView;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Model\UserCourse;
use PHPUnit\Framework\TestCase;

class HasUpdatesCheckerTest extends TestCase
{
    public function testGetUpdatesInfoWithoutDate()
    {
        $dateTime = null;
        $date = new \DateTime();
        $userCourse = $this->prophesize(UserCourse::class);
        $course = $this->prophesize(Course::class);
        $folder = $this->prophesize(Folder::class);
        $session = $this->prophesize(Session::class);

        $userCourse->getCourse()->willReturn($course->reveal());
        $userCourse->getAssignedAt()->willReturn($date);

        $course->isEnabled()->willReturn(true);

        $course->getSessions()->willReturn([$session->reveal()]);
        $course->getFolders()->willReturn([$folder->reveal()]);

        // Expected
        $course->getUpdatedAt()->shouldBeCalled()->willReturn($date);
        $course->getSize()->shouldBeCalled()->willReturn(123);

        $session->getUpdatedAt()->shouldBeCalled()->willReturn($date);
        $session->getSize()->shouldBeCalled()->willReturn(123);
        $session->getContentUpdatedAt()->shouldBeCalled()->willReturn($date);
        $session->getContentSize()->shouldBeCalled()->willReturn(4321);

        $folder->getUpdatedAt()->shouldBeCalled()->willReturn($date);
        $folder->getSize()->shouldBeCalled()->willReturn(987);

        // Checker
        $hasUpdatesChecker = new HasUpdatesChecker();
        $result = $hasUpdatesChecker->getUpdatesInfo([$userCourse->reveal()], $dateTime);

        $expected = [
            new CourseUpdateView($date, 123),
            new SessionUpdateView($date, 123, $date, 4321),
            new FolderUpdateView($date, 987)
        ];

        $this->assertEquals($expected, $result);
    }

    public function testGetUpdatesInfo()
    {
        $dateTime    = new \DateTime('2017-06-06 10:10:10.000');
        $dateCourse1 = new \DateTime('2017-06-12 10:10:10.000');
        $dateCourse2 = new \DateTime('2017-06-02 10:10:10.000');
        $dateFolder1 = new \DateTime('2017-06-01 10:10:10.000');
        $dateFolder2 = new \DateTime('2017-06-12 10:10:10.000');
        // Updated
        $dateSession1 = new \DateTime('2017-06-08 10:10:10.000');
        $dateSession1Content = new \DateTime('2017-06-01 10:10:10.000');
        // None updated
        $dateSession2 = new \DateTime('2017-06-01 10:10:10.000');
        $dateSession2Content = new \DateTime('2017-06-01 10:10:10.000');
        // Both updated
        $dateSession3 = new \DateTime('2017-06-10 10:10:10.000');
        $dateSession3Content = new \DateTime('2017-06-12 10:10:10.000');
        // Content updated
        $dateSession4 = new \DateTime('2017-06-01 10:10:10.000');
        $dateSession4Content = new \DateTime('2017-06-12 10:10:10.000');

        $userCourse1 = $this->prophesize(UserCourse::class);
        $userCourse2 = $this->prophesize(UserCourse::class);

        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $folder1 = $this->prophesize(Folder::class);
        $folder2 = $this->prophesize(Folder::class);
        $session1 = $this->prophesize(Session::class);
        $session2 = $this->prophesize(Session::class);
        $session3 = $this->prophesize(Session::class);
        $session4 = $this->prophesize(Session::class);

        $userCourse1->getCourse()->willReturn($course1->reveal());
        $userCourse1->getAssignedAt()->willReturn($dateTime);
        $userCourse2->getCourse()->willReturn($course2->reveal());
        $userCourse2->getAssignedAt()->willReturn($dateTime);

        $course1->isEnabled()->willReturn(true);
        $course2->isEnabled()->willReturn(true);

        $course1->getSessions()->willReturn([$session1->reveal(), $session2->reveal()]);
        $course1->getFolders()->willReturn([$folder1->reveal()]);
        $course2->getSessions()->willReturn([$session3->reveal(), $session4->reveal()]);
        $course2->getFolders()->willReturn([$folder2->reveal()]);

        // Expected
        $course1->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse1);
        $course2->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse2);
        $course1->getSize()->shouldBeCalled()->willReturn(123);
        $course2->getSize()->shouldNotBeCalled();

        $session1->getUpdatedAt()->shouldBeCalled()->willReturn($dateSession1);
        $session1->getContentUpdatedAt()->shouldBeCalled()->willReturn($dateSession1Content);
        $session1->getSize()->shouldBeCalled()->willReturn(123);
        $session1->getContentSize()->shouldNotBeCalled();

        $session2->getUpdatedAt()->shouldBeCalled()->willReturn($dateSession2);
        $session2->getContentUpdatedAt()->shouldBeCalled()->willReturn($dateSession2Content);
        $session2->getSize()->shouldNotBeCalled();
        $session2->getContentSize()->shouldNotBeCalled();

        $session3->getUpdatedAt()->shouldBeCalled()->willReturn($dateSession3);
        $session3->getContentUpdatedAt()->shouldBeCalled()->willReturn($dateSession3Content);
        $session3->getSize()->shouldBeCalled()->willReturn(9);
        $session3->getContentSize()->shouldBeCalled()->willReturn(6789);

        $session4->getUpdatedAt()->shouldBeCalled()->willReturn($dateSession4);
        $session4->getContentUpdatedAt()->shouldBeCalled()->willReturn($dateSession4Content);
        $session4->getSize()->shouldNotBeCalled();
        $session4->getContentSize()->shouldBeCalled()->willReturn(45632);

        $folder1->getUpdatedAt()->shouldBeCalled()->willReturn($dateFolder1);
        $folder1->getSize()->shouldNotBeCalled();
        $folder2->getUpdatedAt()->shouldBeCalled()->willReturn($dateFolder2);
        $folder2->getSize()->shouldBeCalled()->willReturn(987);

        // Checker
        $hasUpdatesChecker = new HasUpdatesChecker();
        $result = $hasUpdatesChecker->getUpdatesInfo([$userCourse1->reveal(), $userCourse2->reveal()], $dateTime);

        $expected = [
            new CourseUpdateView($dateCourse1, 123),
            new SessionUpdateView($dateSession1, 123, $dateSession1Content, 0),
            new SessionUpdateView($dateSession3, 9, $dateSession3Content, 6789),
            new SessionUpdateView($dateSession4, 0, $dateSession4Content, 45632),
            new FolderUpdateView($dateFolder2, 987),
        ];

        $this->assertEquals($expected, $result);
    }

    public function testNewAssignedCourse()
    {
        $lastUpdatedAt    = new \DateTime('2017-03-01 00:00:00');
        $dateCourse1 = new \DateTime('2017-01-01 00:00:00');
        $dateCourse2 = new \DateTime('2017-01-01 00:00:00');
        $dateAssigned1 = new \DateTime('2017-02-01 00:00:00');
        $dateAssigned2 = new \DateTime('2017-04-01 00:00:00');

        $userCourse1 = $this->prophesize(UserCourse::class);
        $userCourse2 = $this->prophesize(UserCourse::class);
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $folder1 = $this->prophesize(Folder::class);
        $session1 = $this->prophesize(Session::class);
        $session2 = $this->prophesize(Session::class);

        $userCourse1->getCourse()->willReturn($course1->reveal());
        $userCourse1->getAssignedAt()->willReturn($dateAssigned1);

        $userCourse2->getCourse()->willReturn($course2->reveal());
        $userCourse2->getAssignedAt()->willReturn($dateAssigned2);

        $course1->isEnabled()->willReturn(true);
        $course2->isEnabled()->willReturn(true);

        $course1->getSessions()->willReturn([$session1->reveal()]);
        $course1->getFolders()->willReturn([$folder1->reveal()]);
        $course2->getSessions()->willReturn([$session2->reveal()]);
        $course2->getFolders()->willReturn([]);

        // Expected
        $course1->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse1);
        $course2->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse2);

        $course2->getSize()->shouldBeCalled()->willReturn(321);

        $session1->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse1);
        $session1->getContentUpdatedAt()->shouldBeCalled()->willReturn($dateCourse1);

        $folder1->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse1);

        $session2->getUpdatedAt()->shouldBeCalled()->willReturn($dateCourse2);
        $session2->getSize()->shouldBeCalled()->willReturn(333);
        $session2->getContentUpdatedAt()->shouldBeCalled()->willReturn($dateCourse2);
        $session2->getContentSize()->shouldBeCalled()->willReturn(999);

        // Checker
        $hasUpdatesChecker = new HasUpdatesChecker();
        $result = $hasUpdatesChecker->getUpdatesInfo([$userCourse1->reveal(), $userCourse2->reveal()], $lastUpdatedAt);

        $expected = [
            new CourseUpdateView($dateCourse1, 321),
            new SessionUpdateView($dateCourse2, 333, $dateCourse2, 999),
        ];

        $this->assertEquals($expected, $result);
    }
}
