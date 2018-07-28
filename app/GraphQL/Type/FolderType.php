<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class FolderType extends GraphQLType {
	protected $attributes = [
		'name' => 'Folder',
		'description' => 'A folder (contains sessions)',
	];

	public function fields() {
		return [
			'hash_id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Folder hashID',
			],
			'order' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Order number of the folder',
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Name of the folder',
			],
			'updated_at' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Date folder was updated in format YYYY-MM-DD HH:MM',
			],
			'sessions' => [
				'type' => Type::listOf(GraphQL::type('Session')),
				'description' => 'Sessions of the folder',
			],
			'quiz' => [
				'type' => GraphQL::type('Quiz'),
				'description' => 'Quiz of the folder',
			]
		];
	}
}
