<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Form\Type\User;

use App\Domain\Model\Institution;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChoiceType extends AbstractType
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('institution');
        $resolver->setAllowedTypes('institution', Institution::class);
        $resolver->setDefaults([
            'class'            => User::class,
            'choice_label'     => function (User $user) {
                return sprintf('%s %s (%s)',
                    $user->getLastName(),
                    $user->getFirstName(),
                    $user->getPhoneNumber()
                );
            },
            'repositoryMethod' => function (UserRepositoryInterface $userRepository, Institution $institution) {
                return $userRepository->findByInstitution($institution);
            },
            'choices'          => function (Options $options) {
                return $options['repositoryMethod']($this->userRepository, $options['institution']);
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

}
