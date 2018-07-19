<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Session;

class SessionType extends GraphQLType {
	protected $attributes = [
		'name' => 'Session',
		'description' => 'A session that contains content of information',
		'model' => Session::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Session ID',
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Name of the session',
			],
			'order' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Order number of the session',
			],
			'teacher' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Teacher of the session',
			],
			'content' => [
				'type' => Type::string(),
				'description' => 'HTML content of the Session',
			],
			'progression_lock' => [
				'type' => Type::nonNull(Type::boolean()),
				'description' => 'Is the session progression locked?',
			],
			'has_progressed' => [
				'type' => Type::boolean(),
                'description' => 'Has the student progressed already from this session?',
                'selectable' => false,
			],
			'updated_at' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Date session was updated in format YYYY-MM-DD HH:MM',
			],
			'files' => [
				'type' => Type::listOf(GraphQL::type('File')),
				'description' => 'Files of the session',
			],
			'quiz' => [
				'type' => GraphQL::type('Quiz'),
				'description' => 'Quiz of the session',
			]
		];
	}
}
