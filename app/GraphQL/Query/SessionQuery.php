<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\Session;

class SessionQuery extends Query {
	protected $attributes = [
		'name' => 'Session Query',
		'description' => 'A query of the sessions of a course'
	];

	public function type() {
		return Type::listOf(GraphQL::type('Session'));
	}

	public function resolve($root, $args) {
        return Session::enabled()->get();
	}
}
