<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Adapter;

use App\Application\Command\Command;
use League\Tactician\CommandBus as TacticianCommandBus;
use App\Infrastructure\Adapter\CommandBus;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    public function testHandle()
    {
        $command = $this->prophesize(Command::class);

        $tacticianCommandBus = $this->prophesize(TacticianCommandBus::class);
        $tacticianCommandBus->handle($command->reveal())->shouldBeCalled()->willReturn(null);

        $commandBus = new CommandBus($tacticianCommandBus->reveal());
        $result = $commandBus->handle($command->reveal());

        $this->assertEquals(null, $result);
    }
}
