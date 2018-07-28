<?php

namespace App\GraphQL\Normalizer;

use App\File;
use App\Session;
use Vinkla\Hashids\Facades\Hashids;

class SessionNormalizer {
    /** @var FileNormalizer */
    private $fileNormalizer;

    /**
     * SessionNormalizer constructor.
     * @param FileNormalizer $fileNormalizer
     */
    public function __construct(FileNormalizer $fileNormalizer) {
        $this->fileNormalizer = $fileNormalizer;
    }

    /**
     * Normalize a session's data
     *
     * @param Session $session
     * @param File[] $files
     * @param bool $progressed
     *
     * @return array
     */
    public function normalize(Session $session, array $files, bool $progressed = false): array {
        return [
            'hash_id' => Hashids::connection('session')->encode($session->id),
            'order' => $session->order,
            'name' => $session->name,
            'content' => $session->content,
            'content_updated_at' => $session->content_updated_at,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
            'progressed' => $progressed,
            'progression_lock' => $session->progression_lock,
            'files' => array_map(function (File $file) {
                return $this->fileNormalizer->normalize($file);
            }, $files),
        ];
    }
}