<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Import;

use App\Application\Command\File\Remove;
use App\Application\Command\File\RemoveHandler;
use App\Application\Command\User\Create;
use App\Application\Command\User\CreateHandler;
use App\Application\Query\User\Import\ImportQuery;
use App\Application\Query\User\Import\ImportQueryHandler;

class ConfirmImportHandler
{
    /** @var ImportQueryHandler */
    private $importQueryHandler;

    /** @var CreateHandler */
    private $createHandler;

    /** @var RemoveHandler */
    private $removeHandler;

    /** @var string */
    private $importDir;

    /**
     * @param ImportQueryHandler $importQueryHandler
     * @param CreateHandler      $createHandler
     * @param RemoveHandler      $removeHandler
     * @param string             $importDir
     */
    public function __construct(
        ImportQueryHandler $importQueryHandler,
        CreateHandler $createHandler,
        RemoveHandler $removeHandler,
        string $importDir
    ) {
        $this->importQueryHandler = $importQueryHandler;
        $this->createHandler = $createHandler;
        $this->removeHandler = $removeHandler;
        $this->importDir = $importDir;
    }

    /**
     * @param ConfirmImport $command
     */
    public function handle(ConfirmImport $command)
    {
        $userImportListView = $this->importQueryHandler->handle(new ImportQuery($command->file));

        foreach ($userImportListView->userImportViews as $userImportView) {
            if ($userImportView->hasError()) {
                continue;
            }

            $create              = new Create();
            $create->firstName   = $userImportView->firstName;
            $create->lastName    = $userImportView->lastName;
            $create->phoneNumber = $userImportView->phoneNumber;
            $create->locale      = $userImportView->language;
            $create->country     = $userImportView->country;

            $this->createHandler->handle($create);
        }

        $this->removeHandler->handle(new Remove($command->file, $this->importDir));
    }
}
