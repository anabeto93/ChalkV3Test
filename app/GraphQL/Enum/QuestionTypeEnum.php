<?php

namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class QuestionTypeEnum extends GraphQLType {

    protected $enumObject = true;

    protected $attributes = [
        'name' => 'QuestionType',
        'description' => 'The type of question (multiple choice etc...)',
        'values' => [
            'multiple_choice' => 'multiple_choice',
            'short_exact' => 'short_exact',
        ],
    ];

}
