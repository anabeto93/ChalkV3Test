<?php

namespace App\GraphQL\Query;

use GraphQL;
use Rebing\GraphQL\Support\Query;

use App\Session;

class SessionContentQuery extends Query {
	protected $attributes = [
		'name' => 'Session Content Query',
		'description' => 'A query of the contents of a session (text, files, quizzes)'
	];

	public function type() {
		return GraphQL::type('Session');
	}

	public function resolve($root, $args) {
        return Session::enabled()->first();
	}
}
