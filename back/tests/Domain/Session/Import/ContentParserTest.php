<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Session\Import;


use App\Domain\Exception\Session\Import\ImageWithoutSrcException;
use App\Domain\Session\Import\ContentParsedView;
use App\Domain\Session\Import\ContentParser;
use PHPUnit\Framework\TestCase;

class ContentParserTest extends TestCase
{
    public function testParse()
    {
        $contentParser = new ContentParser();
        $result = $contentParser->parse(__DIR__ . '/index.html', '/image/location');

        $expectedContent = '
    <h1>Hello world</h1>
    <div>
        <img src="/image/location/test.jpg"></div>
    <p>Lorem ipsum</p>
    <sidebar><img src="/image/location/sidebar.jpg"></sidebar><footer><h4>Good bye</h4>
        <div class="footer">
            <div class="footer-bis">
                <img src="/image/location/footer.jpg"></div>
        </div>
    </footer>
';

        $expected = new ContentParsedView($expectedContent, ['test.jpg', 'sidebar.jpg', 'footer.jpg',]);

        $this->assertEquals($expected, $result);
    }

    public function testParseWithError()
    {
        $this->setExpectedException(ImageWithoutSrcException::class);

        $contentParser = new ContentParser();
        $contentParser->parse(__DIR__ . '/indexWithError.html', '/image/location');
    }
}
