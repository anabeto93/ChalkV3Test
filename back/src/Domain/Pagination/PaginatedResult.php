<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Pagination;

use Countable;
use Iterator;

class PaginatedResult implements Countable, Iterator
{
    /** @var array */
    public $results;

    /** @var int */
    public $page;

    /** @var int */
    public $limit;

    /** @var int */
    public $total;

    /** @var int */
    public $pages;

    /**
     * @param array $results
     * @param int   $page
     * @param int   $limit
     * @param int   $total
     */
    public function __construct(array $results, int $page, int $limit, int $total)
    {
        $this->results      = array_values($results);
        $this->page         = $page;
        $this->limit        = $limit;
        $this->total        = $total;
        $this->pages        = (int) ceil($total / $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->results);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->results);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->results);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        next($this->results);
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return key($this->results) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->results);
    }

    /**
     * @param \Closure $closure
     *
     * @return array
     */
    public function map(\Closure $closure): array
    {
        return array_map($closure, $this->results);
    }
}
