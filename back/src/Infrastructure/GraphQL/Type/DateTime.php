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
    const FORMAT = 'Y-m-d H:i';

    /**
     * @var string
     */
    public $description = 'The `DateTime` scalar type represents textual data, represented as UTF-8
        character sequences. The DateTime type have the format 2017-01-15 10:00';

    /**
     * @param \DateTime $value
     *
     * @return string
     */
    public static function serialize(\DateTime $value): string
    {
        return $value->format(self::FORMAT);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function parseValue($value)
    {
        return \DateTime::createFromFormat(self::FORMAT, $value);
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     */
    public static function parseLiteral($valueNode)
    {
        return \DateTime::createFromFormat(self::FORMAT, $valueNode->value);
    }
}
