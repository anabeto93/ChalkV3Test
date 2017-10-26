<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User\Import;

use App\Application\Command\File\RemoveHandler;
use App\Application\Command\User\Create;
use App\Application\Command\User\CreateHandler;
use App\Application\Command\User\Import\ConfirmImport;
use App\Application\Command\User\Import\ConfirmImportHandler;
use App\Application\Query\User\Import\ImportQuery;
use App\Application\Query\User\Import\ImportQueryHandler;
use App\Application\View\User\Import\UserImportListView;
use App\Application\View\User\Import\UserImportView;
use App\Domain\Model\Upload\File;
use PHPUnit\Framework\TestCase;

class ConfirmImportHandlerTest extends TestCase
{
    public function testHandle()
    {
        $file = $this->prophesize(File::class);
        $importQueryHandler = $this->prophesize(ImportQueryHandler::class);
        $createHandler = $this->prophesize(CreateHandler::class);
        $removeHandler = $this->prophesize(RemoveHandler::class);
        $importDir = '/tmp/';

        $user1 = new UserImportView(
            'firstName1',
            'lastName1',
            '+1231123123',
            'FR',
            'fr'
        );
        $user2 = new UserImportView(
            'firstName2',
            'lastName2',
            '+1231123789',
            'GB',
            'en'
        );
        $user3 = new UserImportView(
            'firstName2',
            'lastName2',
            '+1231123789',
            'GB',
            'en'
        );
        $user3->addError('error');
        $users = [
            $user1,
            $user2,
            $user3,
        ];
        $userImportListView = new UserImportListView($users);
        $importQueryHandler
            ->handle(new ImportQuery($file->reveal()))
            ->shouldBeCalled()
            ->willReturn($userImportListView)
        ;

        $createUser1 = new Create();
        $createUser1->firstName = 'firstName1';
        $createUser1->lastName = 'lastName1';
        $createUser1->phoneNumber = '+1231123123';
        $createUser1->country = 'FR';
        $createUser1->locale = 'fr';
        $createHandler->handle($createUser1)->shouldBeCalled();

        $createUser2 = new Create();
        $createUser2->firstName = 'firstName2';
        $createUser2->lastName = 'lastName2';
        $createUser2->phoneNumber = '+1231123789';
        $createUser2->country = 'GB';
        $createUser2->locale = 'en';
        $createHandler->handle($createUser2)->shouldBeCalled();

        $handler = new ConfirmImportHandler(
            $importQueryHandler->reveal(),
            $createHandler->reveal(),
            $removeHandler->reveal(),
            $importDir
        );
        $confirmImport = new ConfirmImport($file->reveal());
        $handler->handle($confirmImport);

    }
}
