<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Folder;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Folder\Delete;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteAction
{
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_folder_list';

    /** @var RouterInterface */
    private $router;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * @param RouterInterface     $router
     * @param CommandBusInterface $commandBus
     * @param FlashBagInterface   $flashBag
     */
    public function __construct(RouterInterface $router, CommandBusInterface $commandBus, FlashBagInterface $flashBag)
    {
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Course $course
     * @param Folder $folder
     *
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException
     */
    public function __invoke(Course $course, Folder $folder): RedirectResponse
    {
        if ($course !== $folder->getCourse()) {
            throw new NotFoundHttpException('The folder can not on another course route');
        }

        $this->commandBus->handle(new Delete($folder));

        $this->flashBag->add('success', 'flash.admin.folder.delete.success');

        return new RedirectResponse(
            $this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, ['course' => $course->getId()])
        );
    }
}
