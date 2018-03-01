<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 01:37
 */

namespace App\Ui\Admin\Form\Type\Institution;


use App\Application\Command\Institution\Create;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractInstitutionType {
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
           'data_class' => Create::class
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix() {
        return 'institution_create';
    }
}