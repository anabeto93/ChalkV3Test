<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Query\Session;

use App\Application\Query\Session\SessionListQuery;
use App\Application\Query\Session\SessionListQueryHandler;
use App\Application\View\Session\SessionView;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class SessionListQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $course = $this->prophesize(Course::class);
        $session1 = $this->prophesize(Session::class);
        $session2 = $this->prophesize(Session::class);
        $session3 = $this->prophesize(Session::class);
        $folder = $this->prophesize(Folder::class);
        $folder->getTitle()->willReturn('Folder title');

        // Mock
        $sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $sessionRepository
            ->findByCourse($course->reveal())
            ->shouldBeCalled()
            ->willReturn([
                $session1->reveal(),
                $session2->reveal(),
                $session3->reveal(),
            ])
        ;
        $session1->getId()->shouldBeCalled()->willReturn(1);
        $session1->getTitle()->shouldBeCalled()->willReturn('title 1');
        $session1->getRank()->shouldBeCalled()->willReturn(1);
        $session1->hasFolder()->shouldBeCalled()->willReturn(false);

        $session2->getId()->shouldBeCalled()->willReturn(2);
        $session2->getTitle()->shouldBeCalled()->willReturn('title 2');
        $session2->getRank()->shouldBeCalled()->willReturn(2);
        $session2->hasFolder()->shouldBeCalled()->willReturn(true);
        $session2->getFolder()->shouldBeCalled()->willReturn($folder->reveal());

        $session3->getId()->shouldBeCalled()->willReturn(3);
        $session3->getTitle()->shouldBeCalled()->willReturn('title 3');
        $session3->getRank()->shouldBeCalled()->willReturn(12);
        $session3->hasFolder()->shouldBeCalled()->willReturn(true);
        $session3->getFolder()->shouldBeCalled()->willReturn($folder->reveal());

        // Handler
        $query = new SessionListQuery($course->reveal());
        $queryHandler = new SessionListQueryHandler($sessionRepository->reveal());
        $result = $queryHandler->handle($query);

        $expected = [
            new SessionView(1, 'title 1', 1, null),
            new SessionView(2, 'title 2', 2, 'Folder title'),
            new SessionView(3, 'title 3', 12, 'Folder title'),
        ];

        $this->assertEquals($expected, $result);
    }
}