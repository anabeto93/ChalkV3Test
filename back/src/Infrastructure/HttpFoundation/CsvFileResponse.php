<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CsvFileResponse extends Response
{
    /**
     * @param string $content
     * @param string $filename
     * @param int    $status
     * @param array  $headers
     */
    public function __construct(
        $content,
        $filename,
        $status = 200,
        $headers = []
    ) {
        parent::__construct($content, $status, $headers);

        $disposition = $this->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $this->headers->set('Content-Disposition', $disposition);
        $this->headers->set('Content-Type', sprintf('text/csv; charset=%s', 'UTF-8'));
    }
}
