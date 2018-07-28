<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SessionType extends GraphQLType {
	protected $attributes = [
		'name' => 'Session',
		'description' => 'A session that contains content of information',
	];

	public function fields() {
		return [
			'hash_id' => [
				'type' => Type::nonNull(Type::string()),
                'description' => 'Session hashID',
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
            'content_updated_at' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Date session content was updated in format YYYY-MM-DD HH:MM',
            ],
			'progression_lock' => [
				'type' => Type::nonNull(Type::boolean()),
				'description' => 'Is the session progression locked?',
			],
			'progressed' => [
				'type' => Type::boolean(),
                'description' => 'Has the student progressed already past this session?',
                'selectable' => false,
			],
            'created_at' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Date session was created in format YYYY-MM-DD HH:MM',
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
