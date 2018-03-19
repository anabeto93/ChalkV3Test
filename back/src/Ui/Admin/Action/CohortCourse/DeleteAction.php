<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 19/03/2018
 * Time: 01:33
 */

namespace App\Ui\Admin\Action\CohortCourse;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\CohortCourse\Delete;
use App\Domain\Model\Cohort;
use App\Domain\Model\Course;
use App\Domain\Model\Institution;
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
     * @param Institution $institution
     * @param Cohort $cohort
     * @param Course $course
     * @return RedirectResponse
     */
    public function __invoke(Institution $institution, Cohort $cohort, Course $course):
    RedirectResponse {
        $cohortCourse = $cohort->getCohortCourse($cohort, $course);

        if(!$cohortCourse) {
            throw new NotFoundHttpException('Failed to delete cohort/course relationship');
        }

        $this->commandBus->handle(new Delete($cohortCourse));

        $this->flashBag->add('success', 'flash.admin.cohortCourse.delete.success');

        return new RedirectResponse(
            $this->router->generate('admin_cohort_course_list', [
                'institution' => $institution->getId(),
                'cohort' => $cohort->getId()
            ])
        );
    }
}