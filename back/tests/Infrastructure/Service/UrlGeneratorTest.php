<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Service;

use App\Infrastructure\Service\UrlGenerator;
use PHPUnit\Framework\TestCase;

class UrlGeneratorTest extends TestCase
{
    /**
     * @dataProvider provideUrls
     *
     * @param string $scheme
     * @param string $domain
     * @param string $expected
     */
    public function testGetBaseUrl(string $scheme, string $domain, string $expected)
    {
        $urlGenerator = new UrlGenerator($scheme, $domain);
        $result = $urlGenerator->getBaseUrl();

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public static function provideUrls(): array
    {
        return [
            ['http', 'api.dev', 'http://api.dev'],
            ['https', 'api.dev', 'https://api.dev'],
            ['https', 'api.chalkboardeducation.dev', 'https://api.chalkboardeducation.dev'],
            ['http', 'api.chalkboardeducation.dev', 'http://api.chalkboardeducation.dev'],
            ['https', 'api.chalkboard-education.dev', 'https://api.chalkboard-education.dev'],
            ['https', 'api.chalkboardeducation.org', 'https://api.chalkboardeducation.org'],
        ];
    }
}