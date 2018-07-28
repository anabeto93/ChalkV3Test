<?php

namespace App\GraphQL\Query;

use App\GraphQL\Normalizer\CourseNormalizer;
use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;


class CoursesQuery extends Query {
    /** @var CourseNormalizer */
    private $courseNormalizer;

    /**
     * CoursesQuery constructor.
     * @param CourseNormalizer $courseNormalizer
     */
    public function __construct(CourseNormalizer $courseNormalizer) {
        $this->courseNormalizer = $courseNormalizer;
    }

    protected $attributes = [
		'name' => 'Course Query',
		'description' => 'A query of the currently authenticated user\'s enabled courses'
	];

	public function type() {
		return Type::listOf(GraphQL::type('Course'));
	}

	public function resolve($root, $args) {
	    $courses = array();

	    $user = User::find(1);
	    $userCourses = $user->courses()->enabled()->get();

	    foreach($userCourses as $userCourse) {
	        $courses[] = $this->courseNormalizer->normalize($userCourse, $user);
        }

	    return $courses;
	}
}
