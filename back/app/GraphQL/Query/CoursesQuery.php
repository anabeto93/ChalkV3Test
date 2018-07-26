<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\Course;

class CoursesQuery extends Query {
	protected $attributes = [
		'name' => 'Course Query',
		'description' => 'A query of the currently authenticated user\'s enabled courses'
	];

	public function type() {
		return Type::listOf(GraphQL::type('Course'));
	}

	public function resolve($root, $args) {
        return Course::enabled()->get();
	}
}
