<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Adapter;

use App\Infrastructure\Adapter\FileStorage;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileStorageTest extends TestCase
{
    /** @var ObjectProphecy */
    private $fileSystem;

    /** @var \DateTimeInterface */
    private $dateTime;

    public function setUp()
    {
        $this->fileSystem = $this->prophesize(Filesystem::class);
        $this->dateTime = new \DateTime('2017/10/10 10:00:00.000');
    }

    public function testCopy()
    {
        $pathFrom = '/tmp/test';
        $pathTo = '/tmp/to';

        $this->fileSystem->copy($pathFrom, $pathTo, true)->shouldBeCalled();

        $fileStorage = new FileStorage($this->fileSystem->reveal(), $this->dateTime);
        $fileStorage->copy($pathFrom, $pathTo);
    }

    public function testExists()
    {
        $path = '/tmp/path';

        $this->fileSystem->exists($path)->shouldBeCalled()->willReturn(true);

        $fileStorage = new FileStorage($this->fileSystem->reveal(), $this->dateTime);
        $expected = $fileStorage->exists($path);

        $this->assertTrue($expected);
    }

    public function testRemove()
    {
        $path = '/tmp/path';

        $this->fileSystem->remove($path)->shouldBeCalled();

        $fileStorage = new FileStorage($this->fileSystem->reveal(), $this->dateTime);
        $fileStorage->remove($path);
    }

    public function testUpload()
    {
        $file = new UploadedFile(__DIR__ . '/../../files/dummy.txt', 'dummy.txt');

        $this->fileSystem->dumpFile(
            Argument::that(function ($element) {
                return preg_match('/\/tmp\/\/2017\/10\//', $element);
            }),
            "dummy\n"
        )->shouldBeCalled();

        $fileStorage = new FileStorage($this->fileSystem->reveal(), $this->dateTime);
        $fileStorage->upload($file, '/tmp');
    }
}
