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
use App\Application\Command\Course\Update;
use App\Domain\Model\Course;
use App\Ui\Admin\Form\Type\Course\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class UpdateAction
{
    /** @var EngineInterface */
    private $engine;

    /** @var RouterInterface */
    private $router;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface    $flashBag
     */
    public function __construct(
        EngineInterface $engine,
        RouterInterface $router,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag
    ) {
        $this->engine = $engine;
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     *
     * @param Course  $course
     *
     * @return Response
     */
    public function __invoke(Request $request, Course $course): Response
    {
        $update = new Update($course);
        $form = $this->formFactory->create(UpdateType::class, $update, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($update);
            $this->flashBag->add('success', 'flash.admin.course.update.success');

            return new RedirectResponse($this->router->generate('admin_course_list'));
        }

        return $this->engine->renderResponse('Admin/Course/update.html.twig', [
            'course' => $course,
            'form'   => $form->createView()
        ]);
    }
}
