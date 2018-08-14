<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class QuestionType extends GraphQLType {
	protected $attributes = [
		'name' => 'Question',
		'description' => 'A Question',
	];

	public function fields() {
		return [
			'hash_id' => [
				'type' => Type::nonNull(Type::string()),
                'description' => 'Question hashID',
                'selectable' => false
			],
			'title' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Title of the question',
            ],
			'type' => [
				'type' => Type::nonNull(GraphQL::type('QuestionTypeEnum')),
				'description' => 'Type of the question',
            ],
			'feedback' => [
				'type' => Type::string(),
				'description' => 'Feedback to the question',
            ],
			'question_answers' => [
				'type' => Type::nonNull(Type::listOf(GraphQL::type('QuestionAnswer'))),
				'description' => 'Answers of the question',
            ]
		];
    }
}
