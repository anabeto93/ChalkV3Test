<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 15:39
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;

class Update extends AbstractCohortCommand {
    /** @var Institution */
    public $institution;

    /** @var Cohort */
    public $cohort;

    /**
     * Update constructor.
     * @param Institution $institution
     * @param Cohort $cohort
     */
    public function __construct(Institution $institution, Cohort $cohort) {
        $this->institution = $institution;
        $this->cohort = $cohort;
        $this->title = $cohort->getTitle();
    }
}