<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 13:10
 */

namespace Tests\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\AssignUser;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\AssignUserAction;
use App\Ui\Admin\Form\Type\Cohort\AssignUserType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class AssignUserActionTest extends TestCase {
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

    public function setUp()
    {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
    }

    public function testInvoke() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $cohort = $this->prophesize(Cohort::class);
        $cohort->getUsers()->shouldBeCalled()->willReturn([]);

        $request = new Request();
        $response = new Response();
        $assign = new AssignUser($cohort->reveal());
        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        //Mock
        $this->engine->renderResponse("Admin/Cohort/assign_users.html.twig", [
            'institution' => $institution->reveal(), 'cohort' => $cohort->reveal(), 'form' => $formView
        ])->shouldBeCalled()->willReturn($response);
        $this->formFactory->create(AssignUserType::class, $assign, [])
                        ->shouldBeCalled()
                        ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        //Action
        $assignAction = new AssignUserAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->router->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal()
        );
        $result = $assignAction($request, $institution->reveal(), $cohort->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testInvokeHandle() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $institution->getId()->shouldBeCalled()->willReturn(123);
        $cohort = $this->prophesize(Cohort::class);
        $cohort->getId()->shouldBeCalled()->willReturn(1);
        $cohort->getUsers()->shouldBeCalled()->willReturn([]);

        $request = new Request();
        $assign = new AssignUser($cohort->reveal());
        $form = $this->prophesize(FormInterface::class);
        $form->createView()->shouldNotBeCalled();

        //Mock
        $this->engine->renderResponse(Argument::any())->shouldNotBeCalled();
        $this->formFactory->create(AssignUserType::class, $assign, [])
            ->shouldBeCalled()
            ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);

        $this->commandBus->handle($assign)->shouldBeCalled();
        $this->flashBag->add('success', 'flash.admin.cohort.assign_user.success')->shouldBeCalled();
        $this->router->generate('admin_cohort_user_list', ['institution' => 123, 'cohort' => 1])
            ->shouldBeCalled()
            ->willReturn('/admin/institutions/123/cohorts/1/users');

        //Action
        $assignAction = new AssignUserAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->router->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal()
        );
        $result = $assignAction($request, $institution->reveal(), $cohort->reveal());

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertEquals('/admin/institutions/123/cohorts/1/users', $result->getTargetUrl());
    }
}