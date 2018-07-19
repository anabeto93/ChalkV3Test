<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

use App\Student;

class StudentType extends GraphQLType {
	protected $attributes = [
		'name' => 'Student',
		'description' => 'A student',
		'model' => Student::class,
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Student ID',
			],
			'institution' => [
				'type' => Type::nonNull(GraphQL::type('institution')),
				'description' => 'Institution of student',
			],
			'first_name' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'First name',
			],
			'last_name' => [
				'type' => Type::string(),
				'description' => 'Last name',
			],
			'phone_number' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Phone number',
			],
			'country' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'ISO 3166-1 alpha-2 country code',
            ],
			'country_name' => [
				'type' => Type::nonNull(Type::string()),
                'description' => 'Name of the country',
                'selectable' => false
            ],
			'locale' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'Language locale',
			]
		];
    }

    protected function resolveCountryNameField($root, $args) {
        return "Country locale to string here!";
    }
}
