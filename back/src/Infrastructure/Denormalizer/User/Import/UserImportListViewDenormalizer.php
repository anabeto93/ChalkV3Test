<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Denormalizer\User\Import;

use App\Application\View\User\Import\UserImportListView;
use App\Application\View\User\Import\UserImportView;
use App\Domain\Exception\User\Import\InvalidImportHeaderFileFormatException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserImportListViewDenormalizer implements DenormalizerInterface
{
    const KEY_FIRST_NAME   = 'firstName';
    const KEY_LAST_NAME    = 'lastName';
    const KEY_PHONE_NUMBER = 'phoneNumber';
    const KEY_COUNTRY      = 'country';
    const KEY_LANGUAGE     = 'language';

    const ALLOWED_KEYS = [
        self::KEY_FIRST_NAME,
        self::KEY_LAST_NAME,
        self::KEY_PHONE_NUMBER,
        self::KEY_COUNTRY,
        self::KEY_LANGUAGE,
    ];

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $users = [];

        foreach ($data as $row) {
            $row = $this->cleanRow($row);

            if (!$this->areGivenKeysAllowed(self::ALLOWED_KEYS, $row)) {
                throw new InvalidImportHeaderFileFormatException();
            }

            $user = new UserImportView(
                $row[self::KEY_FIRST_NAME],
                $row[self::KEY_LAST_NAME],
                trim($row[self::KEY_PHONE_NUMBER]),
                $row[self::KEY_COUNTRY],
                $row[self::KEY_LANGUAGE]
            );

            $users[] = $user;
        }

        $userImportListView = new UserImportListView($users);

        return $userImportListView;
    }

    /**
     * @param array $row
     *
     * @return array
     */
    private function cleanRow(array $row): array
    {
        return array_filter($row, function ($index) {
            return !empty($index);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Return true if given keys are exactly the same than in array self::ALLOWED_KEYS
     * Return false otherwise
     *
     * @param array $keys
     * @param array $row
     *
     * @return bool
     */
    private function areGivenKeysAllowed(array $keys, array &$row): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $format === 'csv' && $type === UserImportListView::class;
    }
}
