<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 15:54
 */

namespace App\Ui\Admin\Form\Type\Cohort;


use App\Application\Command\Cohort\Update;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractCohortType {
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Update::class
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cohort_update';
    }
}