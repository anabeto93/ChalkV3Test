<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Language\AST\Node;

class DateTime
{
    /**
     * @param \DateTime $value
     *
     * @return string
     */
    public static function serialize(\DateTime $value)
    {
        return $value->format('Y-m-d H:i:s');
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function parseValue($value)
    {
        return new \DateTime($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     */
    public static function parseLiteral($valueNode)
    {
        return new \DateTime($valueNode->value);
    }
}
