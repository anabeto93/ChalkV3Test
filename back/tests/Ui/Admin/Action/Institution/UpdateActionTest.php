<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 12:02
 */

namespace Tests\Ui\Admin\Action\Institution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Institution\Update;
use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Institution\UpdateAction;
use App\Ui\Admin\Form\Type\Institution\UpdateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UpdateActionTest extends TestCase {
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
        $institution = new Institution('uuid', 'name', new \DateTime());
        $request = new Request();
        $response = new Response();
        $update = new Update($institution);

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        //Mock
        $this->engine->renderResponse(UpdateAction::TEMPLATE, ['institution' => $institution, 'form' =>
        $formView])
                    ->shouldBeCalled()
                    ->willReturn($response);

        $this->formFactory->create(UpdateType::class, $update, ['submit' => true])
                        ->shouldBeCalled()
                        ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        //Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $updateAction($request, $institution);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandleException() {
        //Context
        $institution = new Institution('uuid', 'name', new \DateTime());
        $request = new Request();
        $response = new Response();
        $update = new Update($institution);

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());
        $form->get('name')->shouldBeCalled()->willReturn($form->reveal());
        $form->addError(new FormError('The name is already in use'))->shouldBeCalled();

        //Mock
        $this->engine->renderResponse(UpdateAction::TEMPLATE, ['institution' => $institution, 'form' => $formView])
                    ->shouldBeCalled()
                    ->willReturn($response);

        $this->formFactory->create(UpdateType::class, $update, ['submit' => true])
                        ->shouldBeCalled()
                        ->willReturn($form->reveal());


        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($update)->shouldBeCalled()->willThrow
        (NameAlreadyUsedException::class);
        $this->translator->trans(UpdateAction::TRANS_VALIDATOR_NAME_USED, [], 'validators')
                        ->shouldBeCalled()
                        ->willReturn('The name is already in use');

        //Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $updateAction($request, $institution);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle() {
        //Context
        $institution = new Institution('uuid', 'name', new \DateTime());
        $request = new Request();
        $response = new Response();
        $update = new Update($institution);

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldNotBeCalled();

        //Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory->create(UpdateType::class, $update, ['submit' => true])
            ->shouldBeCalled()
            ->willReturn($form->reveal());


        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($update)->shouldBeCalled();
        $this->flashBag->add('success', 'flash.admin.institution.update.success')->shouldBeCalled();
        $this->router->generate(UpdateAction::ROUTE_REDIRECT_AFTER_SUCCESS)
            ->shouldBeCalled()->willReturn('/admin/institutions');

        //Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );
        $result = $updateAction($request, $institution);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('/admin/institutions', $result->getTargetUrl());
    }
}