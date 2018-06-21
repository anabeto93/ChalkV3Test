<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 20:15
 */

namespace App\Ui\Admin\Form\Type\Course;


use App\Domain\Model\Course;
use App\Domain\Model\Institution;
use App\Domain\Repository\CourseRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseChoiceType extends AbstractType {
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /**
     * CourseChoiceType constructor.
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(CourseRepositoryInterface $courseRepository) {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired('institution');
        $resolver->setAllowedTypes('institution', Institution::class);
        $resolver->setDefaults([
            'class' => Course::class,
            'choice_label' => function(Course $course) {
                return sprintf('%s',
                    $course->getTitle()
                );
            },
            'repositoryMethod' => function(CourseRepositoryInterface $courseRepository, Institution $institution) {
                return $courseRepository->findByInstitution($institution);
            },
            'choices' => function(Options $options) {
                return $options['repositoryMethod']($this->courseRepository, $options['institution']);
            }
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getParent() {
        return ChoiceType::class;
    }
}