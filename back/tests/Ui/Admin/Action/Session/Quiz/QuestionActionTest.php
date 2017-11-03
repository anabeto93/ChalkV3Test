<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Session\Quiz;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\Quiz\Quiz;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Ui\Admin\Action\Session\Quiz\QuestionAction;
use App\Ui\Admin\Form\Type\Quiz\QuestionsType;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class QuestionActionTest extends TestCase
{
    /** @var ObjectProphecy */
    private $engine;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $formFactory;

    /** @var ObjectProphecy */
    private $flashBag;

    /** @var ObjectProphecy */
    private $router;

    /** @var ObjectProphecy */
    private $translator;

    /** @var ObjectProphecy */
    private $questionRepository;

    public function setUp()
    {
        $this->questionRepository = $this->prophesize(QuestionRepositoryInterface::class);
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
    }

    public function testSessionEnablePreview()
    {
        $request = $this->prophesize(Request::class);
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);

        $session->isEnable()->willReturn(true);
        $session->getCourse()->willReturn($course->reveal());

        $this->engine
            ->renderResponse(QuestionAction::TEMPLATE_PREVIEW, [
                'course' => $course->reveal(),
                'session' => $session->reveal(),
                'questions' => [],
            ])
            ->shouldBeCalled()
            ->willReturn(new Response())
        ;
        $this->questionRepository->getQuestionsOfSession($session->reveal())->shouldBeCalled()->willReturn([]);

        $action = new QuestionAction(
            $this->questionRepository->reveal(),
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $action($request->reveal(), $course->reveal(), $session->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvoke()
    {
        $request = $this->prophesize(Request::class);
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);
        $quiz = new Quiz($session->reveal(), []);

        $session->isEnable()->willReturn(false);
        $session->getCourse()->willReturn($course->reveal());

        $form = $this->prophesize(Form::class);
        $form->createView()->willReturn($form->reveal());
        $form->handleRequest($request->reveal())->shouldBeCalled()->willReturn($form);
        $form->isSubmitted()->willReturn(false);

        $this->formFactory
            ->create(QuestionsType::class, $quiz, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;

        $this->engine
            ->renderResponse(QuestionAction::TEMPLATE, [
                'form' => $form->reveal(),
                'course' => $course->reveal(),
                'session' => $session->reveal(),
            ])
            ->shouldBeCalled()
            ->willReturn(new Response())
        ;
        $this->questionRepository->getQuestionsOfSession($session->reveal())->shouldBeCalled()->willReturn([]);

        $action = new QuestionAction(
            $this->questionRepository->reveal(),
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $action($request->reveal(), $course->reveal(), $session->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle()
    {
        $request = $this->prophesize(Request::class);
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);
        $course->getId()->willReturn(1);
        $quiz = new Quiz($session->reveal(), []);

        $session->isEnable()->willReturn(false);
        $session->getCourse()->willReturn($course->reveal());

        $form = $this->prophesize(Form::class);
        $form->createView()->willReturn($form->reveal());
        $form->handleRequest($request->reveal())->shouldBeCalled()->willReturn($form);
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);

        $this->formFactory
            ->create(QuestionsType::class, $quiz, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal())
        ;

        $this->engine
            ->renderResponse(QuestionAction::TEMPLATE, [
                'form' => $form->reveal(),
                'course' => $course->reveal(),
                'session' => $session->reveal(),
            ])
            ->shouldNotBeCalled()
        ;
        $this->questionRepository->getQuestionsOfSession($session->reveal())->shouldBeCalled()->willReturn([]);

        $this->commandBus->handle($quiz)->shouldBeCalled();

        $this->router
            ->generate(QuestionAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['course' => 1])
            ->shouldBeCalled()
            ->willReturn('/route')
        ;
        $this->flashBag->add('success', 'flash.admin.session.quiz.save.success')->shouldBeCalled();

        $action = new QuestionAction(
            $this->questionRepository->reveal(),
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $action($request->reveal(), $course->reveal(), $session->reveal());

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }
}
