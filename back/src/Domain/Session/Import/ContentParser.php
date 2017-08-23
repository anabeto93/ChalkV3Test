<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Import;

use App\Domain\Exception\Session\Import\ImageWithoutSrcException;
use App\Infrastructure\Service\UrlGenerator;
use DOMDocument;

class ContentParser
{
    /** @var UrlGenerator */
    private $urlGenerator;

    /** @var array */
    private $imagesFound = [];

    /**
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $pathOfFileToParse
     * @param string $imageLocationPath
     *
     * @return ContentParsedView
     */
    public function parse(string $pathOfFileToParse, string $imageLocationPath): ContentParsedView
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $result = new DOMDocument();

        $document->loadHTML(file_get_contents($pathOfFileToParse));
        $body = $document->getElementsByTagName('body')->item(0);

        foreach ($body->childNodes as $child){
            $this->exploreNode($child, $imageLocationPath);
            $result->appendChild($result->importNode($child, true));
        }

        $content = $result->saveHTML();
        libxml_use_internal_errors(false);

        return new ContentParsedView($content, $this->imagesFound);
    }

    /**
     * @param \DOMNode $node
     * @param string   $imageLocationPath
     */
    private function exploreNode(\DOMNode $node, string $imageLocationPath)
    {
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child){
                $this->exploreNode($child, $imageLocationPath);
            }
        } else {
            if ($node->nodeName === 'img') {
                $this->treatImage($node, $imageLocationPath);
            }
        }
    }

    /**
     * @param \DOMNode $imageNode
     * @param string   $imageLocationPath
     */
    private function treatImage(\DOMNode $imageNode, string $imageLocationPath)
    {
        $src = $imageNode->getAttribute('src');

        if (!empty($src)) {
            $this->imagesFound[] = $src;

            $imageNode->setAttribute(
                'src',
                sprintf('%s%s/%s', $this->urlGenerator->getBaseUrl(), $imageLocationPath, $src)
            );
        } else {
            throw new ImageWithoutSrcException();
        }
    }
}
