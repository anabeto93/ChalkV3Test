<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 04/04/2018
 * Time: 16:11
 */

namespace Tests\Ui\Admin\Action\Institution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Institution\Create;
use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Ui\Admin\Action\Institution\CreateAction;
use App\Ui\Admin\Form\Type\Institution\CreateType;
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
        $request = new Request();
        $response = new Response();
        $create = new Create();
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        //Mock
        $this->engine->renderResponse("Admin/Institution/create.html.twig", ['form' => $formView])
                    ->shouldBeCalled()
                    ->willReturn($response);

        $this->formFactory->create(CreateType::class, $create, ['submit' => true])
                        ->shouldBeCalled()
                        ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isValid()->shouldBeCalled()->willReturn(false);

        //Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $createAction($request);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandleException() {
        //Context
        $request = new Request();
        $response = new RedirectResponse('/admin/institutions');
        $create = new Create();

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->get('name')->shouldBeCalled()->willReturn($form->reveal());
        $form->addError(new FormError('The name is already in use'))->shouldBeCalled();

        //Mock
        $this->engine->renderResponse('Admin/Institution/create.html.twig', ['form' => $formView])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->formFactory->create(CreateType::class, $create, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($create)
                        ->shouldBeCalled()
                        ->willThrow(NameAlreadyUsedException::class);
        $this->translator->trans('validator.name.alreadyUsed', [], 'validators')
                        ->shouldBeCalled()
                        ->willReturn('The name is already in use');

        //Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $createAction($request);

        $this->assertInstanceOf(Response::class, $result);
    }


    public function testInvokeHandle() {
        //Context
        $request = new Request();
        $response = new RedirectResponse('/admin/institutions');
        $create = new Create();

        $form = $this->prophesize(FormInterface::class);
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
        $this->flashBag->add('success', 'flash.admin.institution.create.success')->shouldBeCalled();
        $this->router->generate('admin_institution_list')->shouldBeCalled()->willReturn('/admin/institutions');

        //Action
        $createAction = new CreateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $createAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEquals('/admin/institutions', $result->getTargetUrl());
    }
}