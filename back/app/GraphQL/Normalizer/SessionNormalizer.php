<?php

namespace App\GraphQL\Normalizer;

use App\File;
use App\Session;
use Illuminate\Support\Collection;
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
     * @param Collection $files
     *
     * @return array
     */
    public function normalize(Session $session, Collection $files = null): array {
        $normalizedFiles = [];

        if (filled($files)) {
            /** @var File $file */
            foreach ($files as $file) {
                $normalizedFiles[] = $this->fileNormalizer->normalize($file);
            }
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
        ];
    }
}
