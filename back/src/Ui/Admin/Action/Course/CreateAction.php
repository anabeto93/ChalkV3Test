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
use App\Application\Command\Course\Create;
use App\Ui\Admin\Form\Type\Course\CreateType;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class CreateAction
{
    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactory */
    private $formFactory;

    /** @var FlashBag */
    private $flashBag;

    /** @var Router */
    private $router;

    /**
     * @param EngineInterface     $engine
     * @param CommandBusInterface $commandBus
     * @param FormFactory         $formFactory
     * @param FlashBag            $flashBag
     * @param Router              $router
     */
    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactory $formFactory,
        FlashBag $flashBag,
        Router $router
    ) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request): Response
    {
        $create = new Create();
        $form = $this->formFactory->create(CreateType::class, $create, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($create);
            $this->flashBag->add('success', 'flash.admin.course.create.success');

            return new RedirectResponse($this->router->generate('admin_course_list'));
        }

        return $this->engine->renderResponse('Admin/Course/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
