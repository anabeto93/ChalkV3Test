<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Form\Type\Folder;

use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderChoiceType extends AbstractType
{
    /** @var FolderRepositoryInterface */
    private $folderRepository;

    /**
     * @param FolderRepositoryInterface $folderRepository
     */
    public function __construct(FolderRepositoryInterface $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('course');
        $resolver->setAllowedTypes('course', Course::class);
        $resolver->setDefaults([
            'class'            => Folder::class,
            'choice_label'     => 'title',
            'repositoryMethod' => function (FolderRepositoryInterface $folderRepository, Course $course) {
                return $folderRepository->findByCourse($course);
            },
            'choices'          => function (Options $options) {
                return $options['repositoryMethod']($this->folderRepository, $options['course']);
            },
        ]);
    }
}
