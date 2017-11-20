<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Session;

use App\Application\Command\Session\Update;
use App\Application\Command\Session\UpdateHandler;
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Session\Import\ContentImporter;
use App\Domain\Size\Calculator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sessionRepository;

    /** @var ObjectProphecy */
    private $calculator;

    /** @var ObjectProphecy */
    private $contentImporter;

    /** @var \DateTimeInterface */
    private $dateTime;

    public function setUp()
    {
        $this->sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $this->calculator = $this->prophesize(Calculator::class);
        $this->contentImporter = $this->prophesize(ContentImporter::class);
        $this->dateTime = new \DateTime();
    }

    public function testHandleWithoutFile()
    {
        $folder = $this->prophesize(Folder::class);
        $session = $this->prophesize(Session::class);
        $session->getUuid()->willReturn('123-123-123');
        $session->getTitle()->willReturn('oldTitle');
        $session->getRank()->willReturn(1);
        $session->needValidation()->willReturn(false);
        $session->getFolder()->willReturn(null);
        $session->isEnabled()->willReturn(false);

        $this->calculator->calculateSize('123-123-1235newTitle')->shouldBeCalled()->willReturn(1234);
        $session->update(
            'newTitle',
            5,
            $folder->reveal(),
            true,
            true,
            1234,
            $this->dateTime
        )->shouldBeCalled();

        $this->sessionRepository->set($session->reveal())->shouldBeCalled();

        $command = new Update($session->reveal());
        $command->title = 'newTitle';
        $command->rank = 5;
        $command->folder = $folder->reveal();
        $command->needValidation = true;
        $command->enabled = true;
        $handler = new UpdateHandler(
            $this->sessionRepository->reveal(),
            $this->calculator->reveal(),
            $this->contentImporter->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }

    public function testHandle()
    {
        $folder = $this->prophesize(Folder::class);
        $session = $this->prophesize(Session::class);
        $session->getUuid()->willReturn('123-123-123');
        $session->getTitle()->willReturn('oldTitle');
        $session->getRank()->willReturn(1);
        $session->needValidation()->willReturn(false);
        $session->isEnabled()->willReturn(false);
        $session->getFolder()->willReturn(null);
        $file = new UploadedFile(__DIR__ . '/UpdateHandlerTest.php', 'application/text');

        $this->calculator->calculateSize('123-123-1235newTitle')->shouldBeCalled()->willReturn(1234);
        $session->update(
            'newTitle',
            5,
            $folder->reveal(),
            true,
            true,
            1234,
            $this->dateTime
        )->shouldBeCalled();

        $this->sessionRepository->set($session->reveal())->shouldBeCalled();

        $this->contentImporter->importUpdateSession(
            $session->reveal(),
            $file,
            $this->dateTime
        )->shouldBeCalled();

        $command = new Update($session->reveal());
        $command->title = 'newTitle';
        $command->rank = 5;
        $command->folder = $folder->reveal();
        $command->needValidation = true;
        $command->content = $file;
        $command->enabled = true;
        $handler = new UpdateHandler(
            $this->sessionRepository->reveal(),
            $this->calculator->reveal(),
            $this->contentImporter->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}
