<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 14:27
 */

namespace Tests\Ui\Admin\Action\Cohort\CohortUser;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\QueryBusInterface;
use App\Application\Command\User\Batch;
use App\Application\Query\Cohort\CohortUser\CohortUserListQuery;
use App\Application\View\User\UserListView;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\CohortUser\ListAction;
use App\Ui\Admin\Form\Type\User\BatchType;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ListActionTest extends TestCase {
    public function testInvoke() {
        //Context
        $request = new Request();
        $institution = $this->prophesize(Institution::class);
        $cohort = $this->prophesize(Cohort::class);
        $users = $this->prophesize(UserListView::class);

        //Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $commandBus = $this->prophesize(CommandBusInterface::class);
        $router = $this->prophesize(RouterInterface::class);
        $formFactory = $this->prophesize(FormFactoryInterface::class);
        $flashBag = $this->prophesize(FlashBagInterface::class);
        $translator = $this->prophesize(TranslatorInterface::class);

        $batchForm = $this->prophesize(FormInterface::class);
        $batchFormView = $this->prophesize(FormView::class);
        $batchForm->createView()->shouldBeCalled()->willReturn($batchFormView->reveal());

        $queryBus->handle(new CohortUserListQuery($cohort->reveal()))
                ->shouldBeCalled()
                ->willReturn($users);

        $response = new Response();
        $engine->renderResponse("Admin/Cohort/CohortUser/list.html.twig", [
            'userList' => $users,
            'institution' => $institution->reveal(),
            'cohort' => $cohort->reveal(),
            'batchForm' => $batchFormView->reveal()
        ])->shouldBeCalled()->willReturn($response);

        $batch = new Batch();

        $formFactory->create(BatchType::class, $batch, ['userViews' => []])
                    ->shouldBeCalled()
                    ->willReturn($batchForm->reveal());

        $batchForm->handleRequest($request)->shouldBeCalled()->willReturn($batchForm->reveal());
        $batchForm->isSubmitted()->shouldBeCalled()->willReturn(false);

        $action = new ListAction(
            $engine->reveal(),
            $queryBus->reveal(),
            $commandBus->reveal(),
            $router->reveal(),
            $formFactory->reveal(),
            $flashBag->reveal(),
            $translator->reveal()
        );

        $result = $action($request, $institution->reveal(), $cohort->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }

    public function tesHandleForm() {
        // Context
        $request = new Request();
        $institution = $this->prophesize(Institution::class);
        $institution->getId()->shouldBeCalled()->willReturn(123);
        $cohort = $this->prophesize(Cohort::class);
        $cohort->getId()->shouldBeCalled()->willReturn(1);
        $users = $this->prophesize(UserListView::class);

        // Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $formFactory = $this->prophesize(FormFactoryInterface::class);
        $router = $this->prophesize(RouterInterface::class);
        $commandBus = $this->prophesize(CommandBusInterface::class);
        $flashBag = $this->prophesize(FlashBagInterface::class);
        $translator = $this->prophesize(TranslatorInterface::class);

        $batchForm = $this->prophesize(FormInterface::class);
        $batchSubmit = $this->prophesize(SubmitButton::class);
        $batchFormView = $this->prophesize(FormView::class);
        $batchForm->createView()->shouldBeCalled()->willReturn($batchFormView->reveal());

        $queryBus->handle(new CohortUserListQuery($cohort->reveal()))->shouldBeCalled()->willReturn
        ($users->reveal());

        $batch = new Batch();

        $formFactory
            ->create(BatchType::class, $batch, ['userViews' => []])
            ->shouldBeCalled()
            ->willReturn($batchForm->reveal())
        ;

        $batchForm->handleRequest($request)->shouldBeCalled()->willReturn($batchForm->reveal());
        $batchForm->isSubmitted()->shouldBeCalled()->willReturn(true);
        $batchForm->isValid()->shouldBeCalled()->willReturn(true);
        $batchForm->get('sendLoginAccessAction')->shouldBeCalled()->willReturn($batchSubmit->reveal());
        $batchSubmit->isClicked()->shouldBeCalled()->willReturn(true);

        $expectedBatch = new Batch();
        $expectedBatch->sendLoginAccessAction = true;

        $commandBus->handle($expectedBatch)->shouldBeCalled()->willReturn(2);

        $translator
            ->transChoice(
                'flash.admin.user.batch.sendLoginAccessAction.success',
                2,
                ['%countUsersNotified%' => 2],
                'flashes'
            )
            ->shouldBeCalled()
            ->willReturn('Login access are sent to 2 users')
        ;

        $flashBag->add('success', 'Login access are sent to 2 users')->shouldBeCalled();

        $router->generate('admin_cohort_user_list', [
            'institution' => $institution->reveal(),
            'cohort' => $cohort->reveal()
        ])->shouldBeCalled()->willReturn('/institutions/123/cohorts/1/users');

        $action = new ListAction(
            $commandBus->reveal(),
            $engine->reveal(),
            $flashBag->reveal(),
            $formFactory->reveal(),
            $queryBus->reveal(),
            $router->reveal(),
            $translator->reveal()
        );

        $result = $action($request, $institution->reveal(), $cohort->reveal());

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }
}