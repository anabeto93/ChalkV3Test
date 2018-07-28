<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;


class CourseType extends GraphQLType {
	protected $attributes = [
		'name' => 'Course',
		'description' => 'A course',
	];

	public function fields() {
		return [
			'hash_id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Course hashID',
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
                'type' => Type::nonNull(DateTime::type()),
				'description' => 'Date course was created in format YYYY-MM-DD HH:MM:SS',
			],
			'updated_at' => [
                'type' => Type::nonNull(DateTime::type()),
				'description' => 'Date course was updated in format YYYY-MM-DD HH:MM:SS',
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
