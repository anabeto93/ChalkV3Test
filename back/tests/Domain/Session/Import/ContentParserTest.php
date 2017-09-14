<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Session\Import;

use App\Domain\Session\Import\ContentParsedView;
use App\Domain\Session\Import\ContentParser;
use App\Infrastructure\Service\UrlGenerator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class ContentParserTest extends TestCase
{
    /** @var ObjectProphecy */
    private $urlGenerator;

    public function setUp()
    {
        $this->urlGenerator = $this->prophesize(UrlGenerator::class);
    }

    public function testParse()
    {
        $this->urlGenerator->getBaseUrl()->shouldBeCalled()->willReturn('http://api.chalkboardedu.dev');
        $contentParser = new ContentParser($this->urlGenerator->reveal());
        $result = $contentParser->parse(__DIR__ . '/index.html', '/image/location');

        $expectedContent = '
    <h1>Hello world</h1>
    <div>
        <img src="http://api.chalkboardedu.dev/image/location/test.jpg" style="width:100%" />
    </div>
    <p>Lorem ipsum</p>
    <sidebar>
        <img style="width:100%" src="http://api.chalkboardedu.dev/image/location/sidebar.jpg"/>
    </sidebar>
    <footer>
        <h4>Good bye</h4>
        <div class="footer">
            <div class="footer-bis">
                <img src="http://api.chalkboardedu.dev/image/location/footer.jpg"/>
            </div>
        </div>
    </footer>
';

        $expected = new ContentParsedView($expectedContent, ['test.jpg', 'sidebar.jpg', 'footer.jpg',]);

        $this->assertEquals($expected, $result);
    }
}
