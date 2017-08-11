<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Form\Type\Folder;

use App\Application\Command\Folder\Create;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractFolderType
{
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
        return 'folder_create';
    }
}
