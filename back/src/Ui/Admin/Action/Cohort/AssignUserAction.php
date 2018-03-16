<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 18:02
 */

namespace App\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\AssignUser;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Cohort\AssignUserType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class AssignUserAction {
    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var RouterInterface */
    private $router;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * AssignUserAction constructor.
     * @param EngineInterface $engine
     * @param CommandBusInterface $commandBus
     * @param RouterInterface $router
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface $flashBag
     */
    public function __construct(EngineInterface $engine, CommandBusInterface $commandBus, RouterInterface $router, FormFactoryInterface $formFactory, FlashBagInterface $flashBag) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @param Institution $institution
     * @param Cohort $cohort
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, Institution $institution, Cohort $cohort): Response {
        $assign = new AssignUser($cohort);

        $form = $this->formFactory->create(AssignUserType::class, $assign, []);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($assign);

            $this->flashBag->add('success', 'flash.admin.cohort.assign_user.success');

            return new RedirectResponse($this->router->generate('admin_cohort_user_list',
                ['institution' => $institution->getId(), 'cohort' => $cohort->getId()]));
        }

        return $this->engine->renderResponse('Admin/Cohort/assign_users.html.twig', [
            'institution' => $institution,
            'cohort' => $cohort,
            'form' => $form->createView()
        ]);
    }
}