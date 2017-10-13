<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\HttpFoundation;

use App\Domain\Charset\Charset;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CsvFileResponse extends Response
{
    /**
     * @param string $content
     * @param string $filename
     * @param int    $status
     * @param array  $headers
     * @param string $charset
     */
    public function __construct(
        string $content,
        string $filename,
        int $status = 200,
        array $headers = [],
        string $charset = Charset::UTF_8
    ) {
        parent::__construct($content, $status, $headers);

        $disposition = $this->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $this->headers->set('Content-Disposition', $disposition);
        $this->headers->set('Content-Type', sprintf('text/csv; charset=%s', $charset));
    }
}
