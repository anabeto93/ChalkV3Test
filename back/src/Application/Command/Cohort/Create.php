<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:49
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\Institution;

class Create extends AbstractCohortCommand {
    /** @var Institution */
    public $institution;

    /**
     * Create constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution) {
        $this->institution = $institution;
    }
}