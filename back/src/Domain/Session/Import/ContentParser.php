<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Import;

use App\Infrastructure\Service\UrlGenerator;

class ContentParser
{
    /** @var UrlGenerator */
    private $urlGenerator;

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
        $content = $this->extractContent(file_get_contents($pathOfFileToParse));
        $imageViews = $this->extractImages($content);

        $imagesFound = [];

        foreach ($imageViews as $imageView) {
            $imagesFound[] = $imageView->src;
            $content = $this->setAbsoluteUrlToImage($content, $imageView, $imageLocationPath);
        }

        return new ContentParsedView($content, $imagesFound);
    }

    /**
     * @param string    $content
     * @param ImageView $imageView
     * @param string    $imageLocationPath
     *
     * @return string
     */
    private function setAbsoluteUrlToImage(string $content, ImageView $imageView, string $imageLocationPath): string
    {
        $newSrc = sprintf('%s%s/%s', $this->urlGenerator->getBaseUrl(), $imageLocationPath, $imageView->src);
        $newTag = str_replace($imageView->src, $newSrc, $imageView->tag);

        return str_replace($imageView->tag, $newTag, $content);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    private function extractContent(string $html): string
    {
        preg_match("/<body[^>]*>(.*?)<\/body>/is", $html, $matches);

        return $matches[1];
    }

    /**
     * @param string $html
     *
     * @return ImageView[]
     */
    private function extractImages(string $html): array
    {
        $imageViews = [];
        preg_match_all('/<img[^>]*src="([^"]*)/i', $html, $matches);

        foreach ($matches[1] as $key => $src) {
            $imageViews[$src] = new ImageView($matches[0][$key], $src);
        }

        return $imageViews;
    }
}
