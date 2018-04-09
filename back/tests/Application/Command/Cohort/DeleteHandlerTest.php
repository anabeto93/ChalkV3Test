<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 23:21
 */

namespace Tests\Application\Command\Cohort;


use App\Application\Command\Cohort\Delete;
use App\Application\Command\Cohort\DeleteHandler;
use App\Domain\Model\Cohort;
use App\Domain\Repository\CohortRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteHandlerTest extends TestCase {
    public function testHandle() {
        //Context
        $cohort = $this->prophesize(Cohort::class);

        //Mock
        $cohortRepository = $this->prophesize(CohortRepositoryInterface::class);
        $cohortRepository->remove($cohort->reveal())->shouldBeCalled();

        //Handler
        $handler = new DeleteHandler($cohortRepository->reveal());
        $handler->handle(new Delete($cohort->reveal()));
    }
}