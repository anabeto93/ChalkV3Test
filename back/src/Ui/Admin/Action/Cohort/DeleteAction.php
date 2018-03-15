<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 16:11
 */

namespace App\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\Delete;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteAction {
    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var RouterInterface */
    private $router;

    /**
     * DeleteAction constructor.
     * @param CommandBusInterface $commandBus
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     */
    public function __construct(CommandBusInterface $commandBus, FlashBagInterface $flashBag, RouterInterface $router) {
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    /**
     * @param Institution $institution
     * @param Cohort $cohort
     * @return RedirectResponse
     */
    public function __invoke(Institution $institution, Cohort $cohort): RedirectResponse {
        if($cohort->getInstitution() !== $institution) {
            throw new NotFoundHttpException(
                sprintf('Cannot delete cohort %s from unrelated institution %s',
                    $cohort->getTitle(), $institution->getName())
            );
        }

        $this->commandBus->handle(new Delete($cohort));

        $this->flashBag->add('success', 'flash.admin.cohort.delete.success');

        return new RedirectResponse($this->router->generate('admin_cohort_list', ['institution'
            => $institution->getId()]));
    }
}