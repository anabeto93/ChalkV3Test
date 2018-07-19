<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Course;

class CourseType extends GraphQLType {
	protected $attributes = [
		'name' => 'Course',
		'description' => 'A course',
		'model' => Course::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Course ID',
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Name of the course',
			],
			'description' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Description of the course',
			],
			'teacher' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Teacher of the course',
			],
			'created_at' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Date course was created in format YYYY-MM-DD HH:MM',
			],
			'updated_at' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Date course was updated in format YYYY-MM-DD HH:MM',
			],
			'folders' => [
				'type' => Type::listOf(GraphQL::type('Folder')),
				'description' => 'Folders of the course',
			],
			'quiz' => [
				'type' => GraphQL::type('Quiz'),
				'description' => 'Quiz of the session',
			]
		];
	}
}
