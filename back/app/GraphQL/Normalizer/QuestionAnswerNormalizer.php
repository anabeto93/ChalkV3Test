<?php


namespace App\GraphQL\Normalizer;


use App\QuestionAnswer;

class QuestionAnswerNormalizer {
    /**
     * @param QuestionAnswer $questionAnswer
     * @return array
     */
    public function normalize(QuestionAnswer $questionAnswer): array {
        return [
            'id' => $questionAnswer->id,
            'title' => $questionAnswer->title,
        ];
    }
}