<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Charset;

final class Charset
{
    const UTF_8        = 'UTF-8';
    const ISO_8859_1   = 'ISO-8859-1';
    const WINDOWS_1252 = 'Windows-1252';

    const CHARSETS = [
        self::WINDOWS_1252,
        self::UTF_8,
        self::ISO_8859_1,
    ];
}
