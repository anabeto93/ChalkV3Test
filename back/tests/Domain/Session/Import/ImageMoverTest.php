<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Session\Import;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Model\Session;
use App\Domain\Repository\Session\FileRepositoryInterface;
use App\Domain\Session\Import\ContentParsedView;
use App\Domain\Session\Import\ImageMover;
use PHPUnit\Framework\TestCase;

class ImageMoverTest extends TestCase
{
    public function testMoveImages()
    {
        $uploadDir = '/srv/app/web';
        $destination = '/content/course/123/session/321';
        $dateTime = new \DateTime();
        $session = $this->prophesize(Session::class);
        $content = new ContentParsedView('content', [
            'sidebar.jpg',
            'footer.jpg',
            'header.jpg',
        ]);

        $expected1 = new Session\File(
            $session->reveal(),
            '/content/course/123/session/321/sidebar.jpg',
            3,
            $dateTime
        );
        $expected2 = new Session\File(
            $session->reveal(),
            '/content/course/123/session/321/footer.jpg',
            20,
            $dateTime
        );
        $expected3 = new Session\File(
            $session->reveal(),
            '/content/course/123/session/321/header.jpg',
            100,
            $dateTime
        );

        $fileRepository = $this->prophesize(FileRepositoryInterface::class);
        $fileStorage = $this->prophesize(FileStorageInterface::class);

        $fileStorage->exists('/tmp/chalkboard_123/sidebar.jpg')->shouldBeCalled()->willReturn(true);
        $fileStorage->exists('/tmp/chalkboard_123/footer.jpg')->shouldBeCalled()->willReturn(true);
        $fileStorage->exists('/tmp/chalkboard_123/header.jpg')->shouldBeCalled()->willReturn(true);

        $fileStorage->copy(
            '/tmp/chalkboard_123/sidebar.jpg',
            '/srv/app/web/content/course/123/session/321/sidebar.jpg'
        )->shouldBeCalled();
        $fileStorage->copy(
            '/tmp/chalkboard_123/footer.jpg',
            '/srv/app/web/content/course/123/session/321/footer.jpg'
        )->shouldBeCalled();
        $fileStorage->copy(
            '/tmp/chalkboard_123/header.jpg',
            '/srv/app/web/content/course/123/session/321/header.jpg'
        )->shouldBeCalled();

        $fileStorage->size('/srv/app/web/content/course/123/session/321/sidebar.jpg')->shouldBeCalled()->willReturn(3);
        $fileStorage->size('/srv/app/web/content/course/123/session/321/footer.jpg')->shouldBeCalled()->willReturn(20);
        $fileStorage->size('/srv/app/web/content/course/123/session/321/header.jpg')->shouldBeCalled()->willReturn(100);

        $fileRepository->add($expected1)->shouldBeCalled();
        $fileRepository->add($expected2)->shouldBeCalled();
        $fileRepository->add($expected3)->shouldBeCalled();

        $imageMover = new ImageMover($fileStorage->reveal(), $fileRepository->reveal(), $uploadDir);
        $size = $imageMover->moveImages($session->reveal(), '/tmp/chalkboard_123', $destination, $content, $dateTime);

        $this->assertEquals(123, $size);
    }
}
