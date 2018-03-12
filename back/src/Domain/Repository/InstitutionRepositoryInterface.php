<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 28/02/2018
 * Time: 02:30
 */

namespace App\Domain\Repository;


use App\Domain\Model\Institution;

interface InstitutionRepositoryInterface {
    /**
     * @param Institution $institution
     */
    public function add(Institution $institution);

    /**
     * @param Institution $institution
     */
    public function set(Institution $institution);

    /**
     * @param Institution $institution
     */
    public function remove(Institution $institution);

    /**
     * @return Institution[]
     */
    public function getAll():array;

    /**
     * @param string $uuid
     * @return Institution|null
     */
    public function getByUuid(string $uuid): ?Institution;


    /**
     * @param string|null $name
     * @return Institution|null
     */
    public function findByName(string $name = null): ?Institution;
}