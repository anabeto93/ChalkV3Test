<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Course;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Course\AssignUser;
use App\Domain\Model\Course;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Course\AssignUserType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class AssignUserAction
{
    const TEMPLATE = 'Admin/Course/assign_users.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_course_list';

    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * @param EngineInterface      $engine
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     * @param FlashBagInterface    $flashBag
     */
    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @param Institution $institution
     * @param Course  $course
     *
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request, Institution $institution, Course $course): Response
    {
        $assign = new AssignUser($course);
        $form = $this->formFactory->create(AssignUserType::class, $assign, [
            'institution' => $institution
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($assign);
            $this->flashBag->add('success', 'flash.admin.course.assign_user.success');

            return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                'institution' => $institution->getId()
            ]));
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'form' => $form->createView(),
            'course' => $course,
            'institution' => $institution
        ]);
    }
}
