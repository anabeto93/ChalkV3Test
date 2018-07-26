<?php

namespace App\GraphQL\Query;

use GraphQL;
use Rebing\GraphQL\Support\Query;

use App\Session;

class SessionQuery extends Query {
	protected $attributes = [
		'name' => 'Session Content Query',
		'description' => 'A query of the contents of a session'
	];

	public function type() {
		return GraphQL::type('Session');
	}

	public function resolve($root, $args) {
        return Session::enabled()->get();
	}
}
