<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Question;

class QuestionType extends GraphQLType {
	protected $attributes = [
		'name' => 'Question',
		'description' => 'A Question',
		'model' => Question::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Question ID',
			],
			'title' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Title of the question',
            ],
			'type' => [
				'type' => Type::nonNull(GraphQL::type('QuestionEnum')),
				'description' => 'Type of the question',
            ],
			'feedback' => [
				'type' => Type::string(),
				'description' => 'Feedback to the question',
            ],
			'answers' => [
				'type' => Type::nonNull(Type::listOf(GraphQL::type('QuestionAnswer'))),
				'description' => 'Answers of the question',
            ]
		];
	}
}
