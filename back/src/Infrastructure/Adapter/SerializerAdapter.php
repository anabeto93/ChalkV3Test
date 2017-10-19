<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Adapter;

use App\Application\Adapter\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;

class SerializerAdapter implements SerializerInterface
{
    /** @var SymfonySerializer */
    private $serializer;

    /**
     * @param SymfonySerializer $serializer
     */
    public function __construct(SymfonySerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $type, $format, array $context = [])
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
