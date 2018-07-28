<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

use App\HasUpdates;

class HasUpdatesType extends GraphQLType {
	protected $attributes = [
		'name' => 'HasUpdates',
		'description' => 'Any updates to download',
	];

	public function fields() {
		return [
			'has_updates' => [
				'type' => Type::nonNull(Type::boolean()),
				'description' => 'Are there updates?',
            ],
			'size' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Size of updates (bytes)',
			]
		];
	}
}
