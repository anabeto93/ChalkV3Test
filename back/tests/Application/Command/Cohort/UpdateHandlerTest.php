<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 22:58
 */

namespace Tests\Application\Command\Cohort;


use App\Application\Command\Cohort\Update;
use App\Application\Command\Cohort\UpdateHandler;
use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Domain\Repository\CohortRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class UpdateHandlerTest extends TestCase {
    /** @var ObjectProphecy */
    private $cohortRepository;

    /** @var \DateTime */
    private $dateTime;

    public function setUp() {
        $this->cohortRepository = $this->prophesize(CohortRepositoryInterface::class);
        $this->dateTime = new \DateTime();
    }

    public function testTitleAlreadyUsed() {
        //Expected
        $this->setExpectedException(TitleAlreadyUsedException::class);

        //Context
        $institution = $this->prophesize(Institution::class);
        $cohortWithSameTitle = $this->prophesize(Cohort::class);
        $cohort = new Cohort(
            'uuid-new',
            $institution->reveal(),
            'title',
            $this->dateTime
        );

        $command = new Update($institution->reveal(), $cohort);
        $command->title = 'Cohort Title';

        //Mock
        $this->cohortRepository->findByInstitutionAndTitle($institution->reveal(), 'Cohort Title')
                            ->shouldBeCalled()
                            ->willReturn($cohortWithSameTitle->reveal());

        //Handler
        $handler = new UpdateHandler(
            $this->cohortRepository->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }

    public function testHandle() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $cohort = new Cohort(
            'uuid-new',
            $institution->reveal(),
            'Cohort A',
            new \DateTime('2018-04-04 12:00:00.000')
        );

        $command = new Update($institution->reveal(), $cohort);
        $command->title = 'Cohort B';

        //Expected
        $expected = new Cohort(
            'uuid-new',
            $institution->reveal(),
            'Cohort A',
            new \DateTime('2018-04-04 12:00:00.000')
        );
        $expected->update(
            'Cohort B',
            $this->dateTime
        );

        //Mock
        $this->cohortRepository->findByInstitutionAndTitle($institution->reveal(), 'Cohort B')
                            ->shouldBeCalled()
                            ->willReturn(null);
        $this->cohortRepository->set($expected)->shouldBeCalled();

        //Handler
        $handler = new UpdateHandler(
            $this->cohortRepository->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}