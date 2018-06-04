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
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Course\CreateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class CreateAction
{
    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var RouterInterface */
    private $router;

    /**
     * @param EngineInterface      $engine
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface    $flashBag
     * @param RouterInterface      $router
     */
    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        RouterInterface $router
    ) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param Institution $institution
     *
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, Institution $institution): Response
    {
        $create = new Create($institution);
        $form = $this->formFactory->create(CreateType::class, $create, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($create);
            $this->flashBag->add('success', 'flash.admin.course.create.success');

            return new RedirectResponse($this->router->generate('admin_course_list', [
                'institution' => $institution->getId()
            ]));
        }

        return $this->engine->renderResponse('Admin/Course/create.html.twig', [
            'institution' => $institution,
            'form' => $form->createView()
        ]);
    }
}
