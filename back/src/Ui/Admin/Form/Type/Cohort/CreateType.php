<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 14:02
 */

namespace App\Ui\Admin\Form\Type\Cohort;


use App\Application\Command\Cohort\Create;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractCohortType {
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Create::class
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cohort_create';
    }
}