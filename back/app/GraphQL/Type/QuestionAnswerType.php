<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\QuestionAnswer;

class QuestionAnswerType extends GraphQLType {
	protected $attributes = [
		'name' => 'QuestionAnswer',
		'description' => 'A question\'s optional answers',
		'model' => QuestionAnswer::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Answer ID',
			],
			'title' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Title of the answer',
			]
		];
	}
}
