<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Denormalizer;

use App\Application\View\User\Import\UserImportListView;
use App\Application\View\User\Import\UserImportView;
use App\Infrastructure\Denormalizer\User\Import\UserImportListViewDenormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserImportListViewDenormalizerTest extends TestCase
{
    public function testHandle()
    {
        $file = 'firstName;lastName;phoneNumber;country;language
Jean;Paul;+33123123;FR;fr
Kaci;Ernser;+33321312;GH;en';

        $serializer = new Serializer(
            [
                new UserImportListViewDenormalizer(),
                new ObjectNormalizer()
            ],
            [
                new CsvEncoder()
            ])
        ;

        $user1 = new UserImportView('Jean', 'Paul', '+33123123', 'FR', 'fr');
        $user2 = new UserImportView('Kaci', 'Ernser', '+33321312', 'GH', 'en');
        $users = [$user1, $user2];
        $expected = new UserImportListView($users);
        $result = $serializer->deserialize($file, UserImportListView::class, 'csv', ['csv_delimiter' => ';']);

        $this->assertEquals($expected, $result);
    }
}
