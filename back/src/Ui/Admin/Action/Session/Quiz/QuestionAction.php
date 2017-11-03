<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Session\Quiz;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\Quiz\Quiz;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Ui\Admin\Form\Type\Quiz\QuestionsType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class QuestionAction
{
    const TEMPLATE = 'Admin/Session/Quiz/question.html.twig';
    const TEMPLATE_PREVIEW = 'Admin/Session/Quiz/preview.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_session_list';

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

    /** @var TranslatorInterface */
    private $translator;

    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /**
     * @param QuestionRepositoryInterface $questionRepository
     * @param EngineInterface             $engine
     * @param CommandBusInterface         $commandBus
     * @param FormFactoryInterface        $formFactory
     * @param FlashBagInterface           $flashBag
     * @param RouterInterface             $router
     * @param TranslatorInterface         $translator
     */
    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->questionRepository = $questionRepository;
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param Course  $course
     * @param Session $session
     *
     * @return Response
     */
    public function __invoke(Request $request, Course $course, Session $session): Response
    {
        if ($course !== $session->getCourse()) {
            throw new NotFoundHttpException('This session is not in this course');
        }

        $questions = $this->questionRepository->getQuestionsOfSession($session);

        // if the session is enable, display the preview of the questions but no edit allowed
        if ($session->isEnable()) {
            return $this->engine->renderResponse(self::TEMPLATE_PREVIEW, [
                'course'    => $course,
                'questions' => $questions,
                'session'   => $session,
            ]);
        }

        $quiz = new Quiz($session, $questions);
        $form = $this->formFactory->create(QuestionsType::class, $quiz, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($quiz);

            return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                'course' => $course->getId(),
            ]));
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'course'  => $course,
            'form'    => $form->createView(),
            'session' => $session,
        ]);
    }
}
