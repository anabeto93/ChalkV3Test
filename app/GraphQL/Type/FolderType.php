<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Folder;

class FolderType extends GraphQLType {
	protected $attributes = [
		'name' => 'Folder',
		'description' => 'A folder (contains sessions)',
		'model' => Folder::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Folder ID',
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Name of the folder',
			],
			'order' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Order number of the folder',
			],
			'teacher' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Teacher of the Folder',
			],
			'updated_at' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Date folder was updated in format YYYY-MM-DD HH:MM',
			],
			'sessions' => [
				'type' => Type::listOf(GraphQL::type('session')),
				'description' => 'Sessions of the folder',
			],
			'quiz' => [
				'type' => GraphQL::type('quiz'),
				'description' => 'Quiz of the session',
			]
		];
	}
}
