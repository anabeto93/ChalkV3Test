<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 18:13
 */

namespace App\Ui\Admin\Action\Cohort\CohortUser;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\QueryBusInterface;
use App\Application\Command\User\Batch;
use App\Application\Query\Cohort\CohortUser\CohortUserListQuery;
use App\Application\View\User\UserListView;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\User\BatchType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ListAction {
    /** @var EngineInterface */
    private $engine;

    /** @var QueryBusInterface */
    private $queryBus;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var RouterInterface */
    private $router;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * ListAction constructor.
     * @param EngineInterface $engine
     * @param QueryBusInterface $queryBus
     * @param CommandBusInterface $commandBus
     * @param RouterInterface $router
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     */
    public function __construct(EngineInterface $engine, QueryBusInterface $queryBus,
                                CommandBusInterface $commandBus, RouterInterface $router,
                                FormFactoryInterface $formFactory, FlashBagInterface $flashBag, TranslatorInterface $translator) {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param Institution $institution
     * @param Cohort $cohort
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, Institution $institution, Cohort $cohort): Response {
        /** @var UserListView $userList */
        $userList = $this->queryBus->handle(new CohortUserListQuery($cohort));

        $batch = new Batch();

        $batchForm = $this->formFactory->create(
            BatchType::class,
            $batch,
            ['userViews' => $userList->users]
        );

        $batchForm->handleRequest($request);

        if($batchForm->isSubmitted() && $batchForm->isValid()) {
            $batch->sendLoginAccessAction = $batchForm->get('sendLoginAccessAction')->isClicked();

            if($batch->sendLoginAccessAction) {
                $countUsersNotified = $this->commandBus->handle($batch);

                $this->flashBag->add(
                    $countUsersNotified > 0 ? 'success' : 'warning',
                    $this->translator->transChoice(
                        'flash.admin.user.batch.sendLoginAccessAction.success',
                        $countUsersNotified,
                        ['%countUsersNotified%' => $countUsersNotified],
                        'flashes'
                    )
                );

                return new RedirectResponse($this->router->generate('admin_cohort_list_users',
                    ['cohort' => $cohort->getId()]));
            }
        }

        return $this->engine->renderResponse('Admin/Cohort/CohortUser/list.html.twig', [
            'userList' => $userList,
            'institution' => $institution,
            'cohort' => $cohort,
            'batchForm' => $batchForm->createView()
        ]);
    }
}