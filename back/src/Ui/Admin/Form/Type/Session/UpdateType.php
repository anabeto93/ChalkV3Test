<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Form\Type\Session;

use App\Application\Command\Session\Update;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractSessionType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('content', FileType::class, [
                'required' => false,
                'help'     => 'form.session_update.children.content.help',
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Update::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'session_update';
    }
}
