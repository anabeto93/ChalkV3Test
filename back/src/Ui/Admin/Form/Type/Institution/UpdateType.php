<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 04:35
 */

namespace App\Ui\Admin\Form\Type\Institution;


use App\Application\Command\Institution\Update;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractInstitutionType {
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Update::class
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix() {
        return 'institution_update';
    }
}