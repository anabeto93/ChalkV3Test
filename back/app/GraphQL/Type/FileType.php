<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FileType extends GraphQLType {
	protected $attributes = [
		'name' => 'File',
		'description' => 'A file',
	];

	public function fields() {
		return [
			'url' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Url of the file',
            ],
			'size' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Size of the file',
            ]
		];
	}
}
