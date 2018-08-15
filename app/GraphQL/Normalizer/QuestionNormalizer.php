<?php


namespace App\GraphQL\Normalizer;


use App\Question;
use Vinkla\Hashids\Facades\Hashids;

class QuestionNormalizer {
    /** @var QuestionAnswerNormalizer */
    private $questionAnswerNormalizer;

    /**
     * QuestionNormalizer constructor.
     * @param QuestionAnswerNormalizer $questionAnswerNormalizer
     */
    public function __construct(QuestionAnswerNormalizer $questionAnswerNormalizer) {
        $this->questionAnswerNormalizer = $questionAnswerNormalizer;
    }

    /**
     * @param Question $question
     * @return array
     */
    public function normalize(Question $question): array {
        $normalizedQuestionAnswers = [];

        $questionAnswers = $question->questionAnswers;

        if(filled($questionAnswers)) {
            foreach($questionAnswers as $questionAnswer) {
                $normalizedQuestionAnswers[] = $this->questionAnswerNormalizer->normalize($questionAnswer);
            }
        }

        return [
            'hash_id' => Hashids::connection('question')->encode($question->id),
            'title' => $question->title,
            'type' => $question->type,
            'feedback' => $question->feedback,
            'question_answers' => $normalizedQuestionAnswers
        ];
    }
}