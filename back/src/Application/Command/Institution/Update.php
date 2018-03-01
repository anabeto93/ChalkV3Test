<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 01:07
 */

namespace App\Application\Command\Institution;


use App\Domain\Model\Institution;

class Update extends AbstractInstitutionCommand {
    /** @var Institution */
    public $institution;

    /**
     * Update constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution) {
        $this->institution = $institution;
        $this->name = $institution->getName();
    }
}