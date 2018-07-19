<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Quiz;

class QuizType extends GraphQLType {
	protected $attributes = [
		'name' => 'Quiz',
		'description' => 'A quiz',
		'model' => Quiz::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Quiz ID',
			],
			'title' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Title of the quiz',
            ],
			'questions' => [
				'type' => Type::listOf(GraphQL::type('question')),
				'description' => 'Questions of the quiz',
            ]
		];
	}
}
