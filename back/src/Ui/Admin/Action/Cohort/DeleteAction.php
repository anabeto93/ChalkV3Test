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
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_cohort_list';

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

        if($cohort->getCourses()) {
            $this->flashBag->add('error', 'flash.admin.cohort.delete.error');

            return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS,
                ['institution' => $institution->getId()])
            );
        }

        $this->commandBus->handle(new Delete($cohort));

        $this->flashBag->add('success', 'flash.admin.cohort.delete.success');

        return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, ['institution'
            => $institution->getId()]));
    }
}