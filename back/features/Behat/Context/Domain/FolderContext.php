<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Context\Domain;

use App\Domain\Model\Course;
use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\FolderProxyInterface;

class FolderContext implements Context
{
    /** @var FolderProxyInterface */
    private $folderProxy;

    /**
     * @param FolderProxyInterface $folderProxy
     */
    public function __construct(FolderProxyInterface $folderProxy)
    {
        $this->folderProxy = $folderProxy;
    }

    /**
     * @Given /^there is a folder with the uuid "(?P<uuid>[^"]+)" and the title "(?P<title>[^"]+)" for this course$/
     *
     * @param string $uuid
     * @param string $title
     */
    public function createFolder($uuid, $title)
    {
        $course = $this->folderProxy->getStorage()->get('course');

        if (!$course instanceof Course) {
            throw new \InvalidArgumentException('Course not found');
        }

        $folder = $this->folderProxy->getFolderManager()->create($uuid, $title, $course);
        $this->folderProxy->getStorage()->set('folder', $folder);
    }
}
