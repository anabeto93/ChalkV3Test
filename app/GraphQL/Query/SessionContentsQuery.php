<?php

namespace App\GraphQL\Query;

use App\GraphQL\Normalizer\SessionNormalizer;
use App\Session;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Vinkla\Hashids\Facades\Hashids;

class SessionContentsQuery extends Query {
	protected $attributes = [
		'name' => 'Session Content Query',
		'description' => 'A query of the contents of a session (text, files, quizzes)'
	];

	/** @var SessionNormalizer */
	protected $sessionNormalizer;

    /**
     * SessionContentsQuery constructor.
     * @param SessionNormalizer $sessionNormalizer
     */
    public function __construct(SessionNormalizer $sessionNormalizer) {
        $this->sessionNormalizer = $sessionNormalizer;
    }

    public function type() {
		return GraphQL::type('Session');
	}

    public function args() {
        return [
            'hash_id' => [
                'name' => 'hash_id',
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

	public function resolve($root, $args) {
	    $sessionID = Hashids::connection('session')->decode($args['hash_id']);

	    $session = Session::with(['progressions' => function($query) {
            $query->where('user_id', 1);
        }])->where('id', $sessionID)->first();

        return $this->sessionNormalizer->normalize($session);
	}
}
