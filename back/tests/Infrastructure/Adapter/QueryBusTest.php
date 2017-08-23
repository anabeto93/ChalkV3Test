<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Adapter;

use App\Application\Query\Query;
use App\Infrastructure\Adapter\QueryBus;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;

class QueryBusTest extends TestCase
{
    public function testHandle()
    {
        $query = $this->prophesize(Query::class);

        $tacticianCommandBus = $this->prophesize(CommandBus::class);
        $tacticianCommandBus->handle($query->reveal())->shouldBeCalled()->willReturn(null);

        $queryBus = new QueryBus($tacticianCommandBus->reveal());
        $result = $queryBus->handle($query->reveal());

        $this->assertEquals(null, $result);
    }
}
