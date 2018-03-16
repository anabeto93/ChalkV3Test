<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 18:50
 */

namespace App\Ui\Admin\Action\CohortUser;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\CohortUser\Delete;
use App\Domain\Model\Cohort;
use App\Domain\Model\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteAction {
    /** @var RouterInterface */
    private $router;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * DeleteAction constructor.
     * @param RouterInterface $router
     * @param CommandBusInterface $commandBus
     * @param FlashBagInterface $flashBag
     */
    public function __construct(RouterInterface $router, CommandBusInterface $commandBus, FlashBagInterface $flashBag) {
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Cohort $cohort
     * @param User $user
     * @return RedirectResponse
     */
    public function __invoke(Cohort $cohort, User $user): RedirectResponse {
        $cohortUser = $cohort->getCohortUser($cohort, $user);

        if(!$cohortUser) {
            throw new NotFoundHttpException('Failed to delete cohort/user relationship');
        }

        $this->commandBus->handle(new Delete($cohortUser));

        $this->flashBag->add('success', 'flash.admin.cohortUser.delete.success');

        return new RedirectResponse(
            $this->router->generate('admin_cohort_user_list', ['cohort' => $cohort->getId()])
        );
    }
}