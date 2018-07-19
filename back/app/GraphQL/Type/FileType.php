<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\File;

class FileType extends GraphQLType {
	protected $attributes = [
		'name' => 'File',
		'description' => 'A file',
		'model' => File::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'File ID',
			],
			'path' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Path of the file',
            ]
		];
	}
}
