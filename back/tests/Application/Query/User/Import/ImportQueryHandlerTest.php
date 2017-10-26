<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Query\User\Import;

use App\Application\Adapter\LocaleHelperInterface;
use App\Application\Adapter\SerializerInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Query\User\Import\ImportQuery;
use App\Application\Query\User\Import\ImportQueryHandler;
use App\Application\View\User\Import\UserImportListView;
use App\Application\View\User\Import\UserImportView;
use App\Domain\Model\Upload\File;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class ImportQueryHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $serializer;

    /** @var ObjectProphecy */
    private $translator;

    /** @var ObjectProphecy */
    private $userRepository;

    /** @var ObjectProphecy */
    private $localeHelper;

    /** @var ObjectProphecy */
    private $importDir;

    public function setUp()
    {
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->localeHelper = $this->prophesize(LocaleHelperInterface::class);
        $this->importDir = __DIR__ ;
    }

    public function testHandle()
    {
        $file = $this->prophesize(File::class);
        $file->getPath()->willReturn('/user-import-test.csv');

        $user1 = new UserImportView('firstName1', 'lastName1', '+1231123123', 'FR', 'fr');
        $user2 = new UserImportView('firstName2', 'lastName2', '+1231123789', 'GB', 'en');
        $user3 = new UserImportView('firstName3', 'lastName3', '+1231123788', 'GB', 'en');
        $user4 = new UserImportView('firstName4', 'lastName4', '+1231123123', 'GB', 'en'); // same phone number of other user
        $user5 = new UserImportView('firstName5', 'lastName5', '+33123456789', 'FR', 'fr'); // same phone number from the db
        $user6 = new UserImportView('firstName6', 'lastName6', '+33123999999', 'FR', 'fr');
        $user7 = new UserImportView('firstName7', 'lastName7', '+33123999990', 'AZERTY', 'fr'); // wrong country
        $user8 = new UserImportView('firstName8', 'lastName8', '+33123999991', 'FR', 'de'); // wrong locale
        $user9 = new UserImportView('firstName9', 'lastName9', '33123999992', 'FR', 'de'); // wrong locale and phone number not starting with +
        $users = [
            $user1,
            $user2,
            $user3,
            $user4,
            $user5,
            $user6,
            $user7,
            $user8,
            $user9,
        ];
        $userImportListView = new UserImportListView($users);

        $this->serializer->deserialize(
            "firstName;lastName;phoneNumber;country;language
Jean;Paul;+33123123;FR;fr
Kaci;Ernser;+33321312;GH;en",
            UserImportListView::class,
            'csv',
            [
                'csv_delimiter' => ';'
            ]
        )->shouldBeCalled()->willReturn($userImportListView);

        $this->userRepository->getPhoneNumbers()->shouldBeCalled()->willReturn(['+33123456789' => '+33123456789']);

        $this->localeHelper->isCountryValid('FR')->shouldBeCalled()->willReturn(true);
        $this->localeHelper->isCountryValid('GB')->shouldBeCalled()->willReturn(true);
        $this->localeHelper->isCountryValid('AZERTY')->shouldBeCalled()->willReturn(false);

        $this->translator
            ->trans('validator.phoneNumber.alreadyUsed', [], 'validators')
            ->shouldBeCalled()
            ->willReturn('validator.phoneNumber.alreadyUsed')
        ;
        $this->translator
            ->trans('validator.user.localeNotAuthorize', [], 'validators')
            ->shouldBeCalled()
            ->willReturn('validator.user.localeNotAuthorize')
        ;
        $this->translator
            ->trans('validator.phoneNumber.mustStartWithPlus', [], 'validators')
            ->shouldBeCalled()
            ->willReturn('validator.phoneNumber.mustStartWithPlus')
        ;
        $this->translator
            ->trans('validator.user.countryNotValid', [], 'validators')
            ->shouldBeCalled()
            ->willReturn('validator.user.countryNotValid')
        ;

        // Expected:
        $expectedUser1 = new UserImportView('firstName1', 'lastName1', '+1231123123', 'FR', 'fr');
        $expectedUser2 = new UserImportView('firstName2', 'lastName2', '+1231123789', 'GB', 'en');
        $expectedUser3 = new UserImportView('firstName3', 'lastName3', '+1231123788', 'GB', 'en');
        $expectedUser4 = new UserImportView('firstName4', 'lastName4', '+1231123123', 'GB', 'en');
        $expectedUser5 = new UserImportView('firstName5', 'lastName5', '+33123456789', 'FR', 'fr');
        $expectedUser6 = new UserImportView('firstName6', 'lastName6', '+33123999999', 'FR', 'fr');
        $expectedUser7 = new UserImportView('firstName7', 'lastName7', '+33123999990', 'AZERTY', 'fr');
        $expectedUser8 = new UserImportView('firstName8', 'lastName8', '+33123999991', 'FR', 'de');
        $expectedUser9 = new UserImportView('firstName9', 'lastName9', '33123999992', 'FR', 'de');

        $expectedUser4->addError('validator.phoneNumber.alreadyUsed');
        $expectedUser5->addError('validator.phoneNumber.alreadyUsed');
        $expectedUser7->addError('validator.user.countryNotValid');
        $expectedUser8->addError('validator.user.localeNotAuthorize');
        $expectedUser9->addError('validator.phoneNumber.mustStartWithPlus');
        $expectedUser9->addError('validator.user.localeNotAuthorize');

        $expectedUsers = [
            $expectedUser1,
            $expectedUser2,
            $expectedUser3,
            $expectedUser4,
            $expectedUser5,
            $expectedUser6,
            $expectedUser7,
            $expectedUser8,
            $expectedUser9,
        ];
        $expectedList = new UserImportListView($expectedUsers);

        $handler = new ImportQueryHandler(
            $this->userRepository->reveal(),
            $this->serializer->reveal(),
            $this->localeHelper->reveal(),
            $this->translator->reveal(),
            $this->importDir
        );
        $result = $handler->handle(new ImportQuery($file->reveal()));

        $this->assertEquals($expectedList, $result);
    }
}
