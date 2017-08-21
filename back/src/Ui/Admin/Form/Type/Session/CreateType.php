<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Form\Type\Session;

use App\Application\Command\Session\Create;
use App\Domain\Model\Course;
use App\Ui\Admin\Form\Type\Folder\FolderChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('rank', IntegerType::class, [
                'required' => true,
            ])
            ->add('content', FileType::class, [
                'required' => true,
            ])
            ->add('folder', FolderChoiceType::class, [
                'course'      => $options['course'],
                'placeholder' => '',
                'required'    => false,
            ])
            ->add('needValidation', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('course');
        $resolver->setAllowedTypes('course', Course::class);
        $resolver->setDefaults([
            'data_class' => Create::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'session_create';
    }
}
