<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\InfrastructureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class FixturesLoader extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $files = [
            __DIR__ . '/Course.yml',
            __DIR__ . '/Folder.yml',
            __DIR__ . '/Session.yml',
            __DIR__ . '/Session.File.yml',
            __DIR__ . '/User.yml',
            __DIR__ . '/UserCourse.yml',
            __DIR__ . '/User.Progression.yml',
            __DIR__ . '/Institution.yml',
        ];

        Fixtures::load($files, $manager, []);
    }
}
