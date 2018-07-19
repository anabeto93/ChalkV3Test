<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Institution;

class InstitutionType extends GraphQLType {
	protected $attributes = [
		'name' => 'Institution',
		'description' => 'An institution',
		'model' => Institution::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Institution ID',
			],
			'name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Name of Institution',
			]
		];
	}
}
