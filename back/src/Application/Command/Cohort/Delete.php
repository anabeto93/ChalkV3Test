<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 16:05
 */

namespace App\Application\Command\Cohort;


use App\Application\Command\Command;
use App\Domain\Model\Cohort;

class Delete implements Command {
    /** @var Cohort */
    public $cohort;

    /**
     * Delete constructor.
     * @param Cohort $cohort
     */
    public function __construct(Cohort $cohort) {
        $this->cohort = $cohort;
    }
}