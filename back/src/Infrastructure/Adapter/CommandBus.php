<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Adapter;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Command;
use League\Tactician\CommandBus as TacticianCommandBus;

class CommandBus implements CommandBusInterface
{
    /** @var TacticianCommandBus */
    private $commandBus;

    /**
     * @param TacticianCommandBus $commandBus
     */
    public function __construct(TacticianCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Command $command)
    {
        return $this->commandBus->handle($command);
    }
}
