<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Service;

class UrlGenerator
{
    /** @var string */
    private $scheme;

    /** @var string */
    private $domain;

    /**
     * @param string $scheme
     * @param string $domain
     */
    public function __construct(string $scheme, string $domain)
    {
        $this->scheme = $scheme;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return sprintf('%s://%s', $this->scheme, $this->domain);
    }
}
