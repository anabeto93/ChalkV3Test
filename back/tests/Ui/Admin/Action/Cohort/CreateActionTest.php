<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 06/04/2018
 * Time: 12:46
 */

namespace Tests\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\Create;
use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\CreateAction;
use App\Ui\Admin\Form\Type\Cohort\CreateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CreateActionTest extends TestCase {
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

    public function setUp() {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
    }

    public function testInvoke() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $request = new Request();
        $response = new Response();
        $create = new Create($institution->reveal());

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        //Mock
        $this->engine->renderResponse("Admin/Cohort/create.html.twig", [
            'institution' => $institution->reveal(),
            'form' => $formView
        ])->shouldBeCalled()->willReturn($response);

        $this->formFactory->create(CreateType::class, $create, ['submit' => true])
                        ->shouldBeCalled()
                        ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        //Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request, $institution->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandleException() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $request = new Request();
        $response = new RedirectResponse('/admin/institutions/institution-id/cohorts/create');
        $create = new Create($institution->reveal());

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);

        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->get('title')->shouldBeCalled()->willReturn($form->reveal());
        $form->addError(new FormError('The title is already in use'))->shouldBeCalled();

        //Mock
        $this->engine->renderResponse("Admin/Cohort/create.html.twig", [
            'institution' => $institution->reveal(),
            'form' => $formView
        ])->shouldBeCalled()->willReturn($response);

        $this->formFactory->create(CreateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($create)
                        ->shouldBeCalled()
                        ->willThrow(TitleAlreadyUsedException::class);

        $this->translator->trans('validator.title.alreadyUsed', [], 'validators')
            ->shouldBeCalled()
            ->willReturn('The title is already in use');


        //Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $createAction($request, $institution->reveal());
        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $institution->getId()->shouldBeCalled()->willReturn(123);
        $request = new Request();
        $response = new RedirectResponse('/admin/institutions/institution-id/cohorts');
        $create = new Create($institution->reveal());

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldNotBeCalled();

        //Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();

        $this->formFactory->create(CreateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($create)->shouldBeCalled();
        $this->flashBag->add('success', 'flash.admin.cohort.create.success')->shouldBeCalled();
        $this->router->generate('admin_cohort_list', ['institution' => 123])
                    ->shouldBeCalled()
                    ->willReturn('/admin/institutions/institution-id/cohorts');

        //Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request, $institution->reveal());

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('/admin/institutions/institution-id/cohorts', $result->getTargetUrl());
    }
}