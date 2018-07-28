<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class QuizType extends GraphQLType {
	protected $attributes = [
		'name' => 'Quiz',
		'description' => 'A quiz',
	];

	public function fields() {
		return [
			'hash_id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Quiz hashID',
                'selectable' => false
			],
			'title' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Title of the quiz',
            ],
			'questions' => [
				'type' => Type::listOf(GraphQL::type('Question')),
				'description' => 'Questions of the quiz',
            ]
		];
    }
}
