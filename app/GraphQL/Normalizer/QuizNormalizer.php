<?php

namespace App\GraphQL\Normalizer;

use App\Quiz;
use Vinkla\Hashids\Facades\Hashids;

class QuizNormalizer {
    /** @var QuestionNormalizer */
    private $questionNormalizer;

    /**
     * QuizNormalizer constructor.
     * @param QuestionNormalizer $questionNormalizer
     */
    public function __construct(QuestionNormalizer $questionNormalizer) {
        $this->questionNormalizer = $questionNormalizer;
    }

    /**
     * @param Quiz $quiz
     * @return array
     */
    public function normalize(Quiz $quiz): array {
        $normalizedQuestions = [];

        $questions = $quiz->questions;

        if(filled($questions)) {
            foreach($questions as $question) {
                $normalizedQuestions[] = $this->questionNormalizer->normalize($question);
            }
        }

        return [
            'hash_id' => Hashids::connection('quiz')->encode($quiz->id),
            'title' => $quiz->title,
            'questions' => $normalizedQuestions
        ];
    }
}