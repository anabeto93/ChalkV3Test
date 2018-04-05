<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 22:20
 */

namespace Tests\Application\Command\Cohort;


use App\Application\Command\Cohort\Create;
use App\Application\Command\Cohort\CreateHandler;
use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class CreateHandlerTest extends TestCase {
    /** @var ObjectProphecy */
    private $cohortRepository;

    /** @var ObjectProphecy */
    private $generator;

    /** @var \DateTime */
    private $dateTime;

    public function setUp() {
        $this->cohortRepository = $this->prophesize(CohortRepositoryInterface::class);
        $this->generator = $this->prophesize(Generator::class);
        $this->dateTime = new \DateTime();
    }

    public function testTitleAlreadyUsed() {
        //Expected
        $this->setExpectedException(TitleAlreadyUsedException::class);

        //Context
        $institution = $this->prophesize(Institution::class);
        $cohort = $this->prophesize(Cohort::class);

        //Mock
        $this->cohortRepository->findByInstitutionAndTitle($institution->reveal(), 'title')
                            ->shouldBeCalled()
                            ->willReturn($cohort->reveal());

        //Handler
        $command = new Create($institution->reveal());
        $command->title = 'title';
        $handler = new CreateHandler(
            $this->cohortRepository->reveal(),
            $this->generator->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }

    public function testHandle() {
        //Context
        $institution = $this->prophesize(Institution::class);

        //Expected
        $expectedCohort = new Cohort('uuid-cohort', $institution->reveal(), 'title',
            $this->dateTime);

        //Mock
        $this->cohortRepository->findByInstitutionAndTitle($institution->reveal(), 'title')
            ->shouldBeCalled()->willReturn(null);
        $this->generator->generateUuid()->shouldBeCalled()->willReturn('uuid-cohort');
        $this->cohortRepository->add($expectedCohort)->shouldBeCalled();


        //Handler
        $command = new Create($institution->reveal());
        $command->title = 'title';
        $handler = new CreateHandler(
            $this->cohortRepository->reveal(),
            $this->generator->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}