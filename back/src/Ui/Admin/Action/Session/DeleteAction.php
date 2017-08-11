<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Session;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Session\Delete;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteAction
{
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_session_list';

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
    public function __construct(
        RouterInterface $router,
        CommandBusInterface $commandBus,
        FlashBagInterface $flashBag
    ) {
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Course  $course
     * @param Session $session
     *
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException
     */
    public function __invoke(Course $course, Session $session): RedirectResponse
    {
        if ($course !== $session->getCourse()) {
            throw new NotFoundHttpException('The session is not on the right course');
        }

        $this->commandBus->handle(new Delete($session));

        $this->flashBag->add('success', 'flash.admin.session.delete.success');

        return new RedirectResponse(
            $this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                'course' => $course->getId(),
            ])
        );
    }
}
