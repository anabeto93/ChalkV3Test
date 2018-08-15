<?php

namespace App\GraphQL\Normalizer;

use App\Folder;
use Vinkla\Hashids\Facades\Hashids;

class FolderNormalizer {
    /**
     * Normalize a folder's data
     *
     * @param Folder $folder
     *
     * @return array
     */
    public function normalize(?Folder $folder = null): array {
        if (blank($folder)) {
            return $this->normalizeDefaultFolder();
        }

        return [
            'hash_id' => Hashids::connection('folder')->encode($folder->id),
            'order' => $folder->order,
            'name' => $folder->name,
            'updated_at' => $folder->updatedAt,
        ];
    }

    /**
     * In the case of no folder, a default is used
     *
     * @return array
     */
    private function normalizeDefaultFolder(): array {
        return [
            'hash_id' => Folder::DEFAULT_FOLDER_ID,
            'order' => 0,
            'name' => Folder::DEFAULT_FOLDER,
            'updated_at' => now(),
        ];
    }
}
