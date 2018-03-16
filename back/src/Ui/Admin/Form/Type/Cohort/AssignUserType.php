<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 17:59
 */

namespace App\Ui\Admin\Form\Type\Cohort;


use App\Ui\Admin\Form\Type\User\UserChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AssignUserType extends AbstractCohortType {
    /**
     * @inheritdoc
     */

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('users', UserChoiceType::class,  [
            'expanded' => true,
            'multiple' => true
        ])->add('submit', SubmitType::class);
    }
}