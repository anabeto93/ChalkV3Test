<?php

namespace App\GraphQL\Query;

use GraphQL;
use Rebing\GraphQL\Support\Query;

use App\User;

class UserQuery extends Query {
	protected $attributes = [
		'name' => 'User Query',
		'description' => 'A query of the currently authenticated user'
	];

	public function authorize(array $args) {
        // if(Auth::check()) {
        //     if($args["token_last_used"]) {
        //         Auth::user()->token_last_used = date_create_from_format("U", $args["token_last_used"]);
        //         Auth::user()->save();
        //     }
        // }

        // return Auth::check();

        return true;
    }

	public function type() {
		return GraphQL::type('User');
	}

	public function resolve($root, $args) {
        // return Auth::user();

        return User::find(1);
	}
}
