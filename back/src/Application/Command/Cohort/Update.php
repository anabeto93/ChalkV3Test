<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 15:39
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\Cohort;

class Update extends AbstractCohortCommand {
    /** @var Cohort */
    public $cohort;

    /**
     * Update constructor.
     * @param Cohort $cohort
     */
    public function __construct(Cohort $cohort) {
        $this->cohort = $cohort;
        $this->title = $cohort->getTitle();
    }
}