<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 20:12
 */

namespace App\Ui\Admin\Form\Type\Cohort;


use App\Ui\Admin\Form\Type\Course\CourseChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AssignCourseType extends AbstractCohortType {
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('courses', CourseChoiceType::class, [
            'expanded' => true,
            'multiple' => true
        ])->add('submit', SubmitType::class);
    }
}