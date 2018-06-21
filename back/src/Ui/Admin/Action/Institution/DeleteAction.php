<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 14:44
 */

namespace App\Ui\Admin\Action\Institution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Institution\Delete;
use App\Domain\Model\Institution;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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

    public function __invoke(Institution $institution): RedirectResponse {
        if(count($institution->getCourses()) > 0) {
            $this->flashBag->add('error', 'flash.admin.institution.courses.delete.error');

            return new RedirectResponse($this->router->generate('admin_institution_list'));
        }

        if(count($institution->getUsers()) > 0) {
            $this->flashBag->add('error', 'flash.admin.institution.users.delete.error');

            return new RedirectResponse($this->router->generate('admin_institution_list'));
        }

        foreach ($institution->getCohorts() as $cohort) {
            if ($cohort->getCourses()) {
                $this->flashBag->add('error', 'flash.admin.institution.cohorts.delete.error');

                return new RedirectResponse($this->router->generate('admin_institution_list'));
            }
        }

        $this->commandBus->handle(new Delete($institution));

        $this->flashBag->add('success', 'flash.admin.institution.delete.success');

        return new RedirectResponse($this->router->generate('admin_institution_list'));
    }
}