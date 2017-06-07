<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\DBAL\Driver\PDOMySql;

use Doctrine\DBAL\Platforms\MySqlPlatform;

class Platform extends MySqlPlatform
{
    /**
     * Workaround for truncating tables by disabling foreign_key_checks
     *
     * {@inheritdoc}
     */
    public function getTruncateTableSQL($tableName, $cascade = false)
    {
        return sprintf('SET foreign_key_checks = 0;TRUNCATE %s;SET foreign_key_checks = 1;', $tableName);
    }
}
