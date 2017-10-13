<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\User\Import;

use App\Application\Adapter\LocaleHelperInterface;
use App\Application\Adapter\SerializerInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\View\User\Import\UserImportListView;
use App\Domain\Exception\User\Import\InvalidImportHeaderFileFormatException;
use App\Domain\Repository\UserRepositoryInterface;

class ImportQueryHandler
{
    const AUTHORIZE_LOCALE = [
        'fr',
        'en',
    ];

    /** @var SerializerInterface */
    private $serializer;

    /** @var TranslatorInterface */
    private $translator;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var LocaleHelperInterface */
    private $localeHelper;

    /** @var string */
    private $importDir;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param SerializerInterface     $serializer
     * @param LocaleHelperInterface   $localeHelper
     * @param TranslatorInterface     $translator
     * @param string                  $importDir
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        SerializerInterface $serializer,
        LocaleHelperInterface $localeHelper,
        TranslatorInterface $translator,
        string $importDir
    ) {
        $this->serializer = $serializer;
        $this->translator = $translator;
        $this->userRepository = $userRepository;
        $this->localeHelper = $localeHelper;
        $this->importDir = $importDir;
    }

    /**
     * @param ImportQuery $query
     *
     * @throws InvalidImportHeaderFileFormatException
     *
     * @return UserImportListView
     */
    public function handle(ImportQuery $query): UserImportListView
    {
        /** @var UserImportListView $userImportListView */
        $userImportListView = $this->serializer->deserialize(
            file_get_contents($this->importDir . $query->file->getPath()),
            UserImportListView::class,
            'csv',
            [
                'csv_delimiter' => ';'
            ]
        );

        $phoneNumbers = $this->userRepository->getPhoneNumbers();

        foreach ($userImportListView->userImportViews as $key => $userImportView) {
            if (!preg_match('/^\+/', $userImportView->phoneNumber)) {
                $userImportView->addError($this->translateError('validator.phoneNumber.mustStartWithPlus'));
            }

            if (isset($phoneNumbers[$userImportView->phoneNumber])) {
                $userImportView->addError($this->translateError('validator.phoneNumber.alreadyUsed'));
            }

            if (!in_array($userImportView->language, self::AUTHORIZE_LOCALE, true)) {
                $userImportView->addError($this->translateError('validator.user.localeNotAuthorize'));
            }

            if (!$this->localeHelper->isCountryValid($userImportView->country)) {
                $userImportView->addError($this->translateError('validator.user.countryNotValid'));
            }

            if (!$userImportView->hasError()) {
                $phoneNumbers[$userImportView->phoneNumber] = $userImportView->phoneNumber;
            }
        }

        return $userImportListView;
    }

    /**
     * @param string $translationKey
     * @param array  $parameters
     *
     * @return string
     */
    public function translateError(string $translationKey, array $parameters = []): string
    {
        return  $this->translator->trans($translationKey, $parameters, 'validators');
    }
}
