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
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem;

class FileStorageTest extends TestCase
{
    /** @var ObjectProphecy */
    private $fileSystem;

    public function setUp()
    {
        $this->fileSystem = $this->prophesize(Filesystem::class);
    }

    public function testCopy()
    {
        $pathFrom = '/tmp/test';
        $pathTo = '/tmp/to';

        $this->fileSystem->copy($pathFrom, $pathTo, true)->shouldBeCalled();

        $fileStorage = new FileStorage($this->fileSystem->reveal());
        $fileStorage->copy($pathFrom, $pathTo);
    }

    public function testExists()
    {
        $path = '/tmp/path';

        $this->fileSystem->exists($path)->shouldBeCalled()->willReturn(true);

        $fileStorage = new FileStorage($this->fileSystem->reveal());
        $expected = $fileStorage->exists($path);

        $this->assertTrue($expected);
    }

    public function testRemove()
    {
        $path = '/tmp/path';

        $this->fileSystem->remove($path)->shouldBeCalled();

        $fileStorage = new FileStorage($this->fileSystem->reveal());
        $fileStorage->remove($path);
    }
}
