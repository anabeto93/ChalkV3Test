<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Form\Type\User;

use App\Application\Command\User\Batch;
use App\Application\View\User\UserView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userViewCallBack = function (UserView $userView) {
            return $userView->id;
        };

        $builder
            ->add('userViews', ChoiceType::class, [
                'choices' => $options['userViews'],
                'expanded' => true,
                'multiple' => true,
                'choice_name' => $userViewCallBack,
                'choice_value' => $userViewCallBack,
                'label' => false,
            ])
            ->add('sendLoginAccessAction', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Batch::class]);
        $resolver->setRequired('userViews');
        $resolver->setAllowedTypes('userViews', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user_batch';
    }
}
