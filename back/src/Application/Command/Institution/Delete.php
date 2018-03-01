<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 01:08
 */

namespace App\Application\Command\Institution;


use App\Domain\Model\Institution;

class Delete extends AbstractInstitutionCommand {
    /** @var Institution */
    public $institution;

    /**
     * Delete constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution) {
        $this->institution = $institution;
    }
}