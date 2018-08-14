<?php

namespace App\GraphQL\Normalizer;

use App\File;
use App\Session;
use Illuminate\Support\Collection;
use Vinkla\Hashids\Facades\Hashids;

class SessionNormalizer {
    /** @var FileNormalizer */
    private $fileNormalizer;

    /** @var QuizNormalizer */
    private $quizNormalizer;

    /**
     * SessionNormalizer constructor.
     * @param FileNormalizer $fileNormalizer
     * @param QuizNormalizer $quizNormalizer
     */
    public function __construct(FileNormalizer $fileNormalizer, QuizNormalizer $quizNormalizer) {
        $this->fileNormalizer = $fileNormalizer;
        $this->quizNormalizer = $quizNormalizer;
    }

    /**
     * Normalize a session's data
     *
     * @param Session $session
     *
     * @return array
     */
    public function normalize(Session $session): array {
        $normalizedFiles = [];
        $normalizedQuiz = [];

        $files = $session->files;
        $quiz = $session->quiz;

        if (filled($files)) {
            foreach ($files as $file) {
                $normalizedFiles[] = $this->fileNormalizer->normalize($file);
            }
        }

        if(filled($quiz)) {
            $normalizedQuiz = $this->quizNormalizer->normalize($quiz);
        }

        return [
            'hash_id' => Hashids::connection('session')->encode($session->id),
            'order' => $session->order,
            'name' => $session->name,
            'content' => $session->content,
            'content_updated_at' => $session->content_updated_at,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
            'progressed' => filled($session->progressions),
            'progression_lock' => $session->progression_lock,
            'files' => $normalizedFiles,
            'quiz' => $normalizedQuiz
        ];
    }
}
