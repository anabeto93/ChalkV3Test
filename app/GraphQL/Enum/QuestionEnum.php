<?php

namespace App\GraphQL\Enum;

use Rebing\GraphQL\Support\Type as GraphQLType;

class QuestionEnum extends GraphQLType {

    protected $enumObject = true;

    protected $attributes = [
        'name' => 'question_type',
        'description' => 'The type of question (multiple choice etc...)',
        'values' => [
            'multiple_choice' => 'multiple_choice',
            'simple_exact' => 'simple_exact',
        ],
    ];

}
