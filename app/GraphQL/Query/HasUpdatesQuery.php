<?php

namespace App\GraphQL\Query;

use GraphQL;
use Rebing\GraphQL\Support\Query;


class HasUpdatesQuery extends Query {
	protected $attributes = [
		'name' => 'Updates Checker Query',
		'description' => 'A query to check if the authenticated user has updates'
	];


	public function type() {
		return GraphQL::type('HasUpdates');
	}

	public function resolve($root, $args) {
        return [
            'has_updates' => true,
            'size' => 1024
        ];
	}
}
