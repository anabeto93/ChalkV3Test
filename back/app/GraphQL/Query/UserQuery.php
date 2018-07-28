<?php

namespace App\GraphQL\Query;

use GraphQL;
use Folklore\GraphQL\Support\Query;

use App\User;

class UserQuery extends Query {
	protected $attributes = [
		'name' => 'User Query',
		'description' => 'A query of the currently authenticated user'
	];

	public function type() {
		return GraphQL::type('User');
	}

	public function resolve($root, $args) {
        // return Auth::user();

        return User::find(1);
	}
}
