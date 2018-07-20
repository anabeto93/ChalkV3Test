<?php

namespace App\GraphQL\Query;

use Auth;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;


class HasUpdatesQuery extends Query {
	protected $attributes = [
		'name' => 'Updates Checker Query',
		'description' => 'A query to check if the authenticated user has updates'
	];

	public function authorize(array $args) {
        // return Auth::check();

        return true;
    }

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
