<?php


namespace App\GraphQL\Normalizer;


use App\Course;
use App\Folder;
use App\Session;
use App\User;
use Vinkla\Hashids\Facades\Hashids;

class CourseNormalizer {
    /** @var FolderNormalizer */
    private $folderNormalizer;

    /** @var SessionNormalizer */
    private $sessionNormalizer;

    /**
     * CourseNormalizer constructor.
     * @param FolderNormalizer $folderNormalizer
     * @param SessionNormalizer $sessionNormalizer
     */
    public function __construct(FolderNormalizer $folderNormalizer, SessionNormalizer $sessionNormalizer) {
        $this->folderNormalizer = $folderNormalizer;
        $this->sessionNormalizer = $sessionNormalizer;
    }

    /**
     * Normalize a user's course's data (including folders, sessions)
     *
     * @param Course $course
     * @param User   $user
     *
     * @return array
     */
    public function normalize(Course $course, User $user): array
    {
        //Eager load relations (minimized querying)
        $sessions = $course->sessions()->enabled()
                        ->with([
                            'folder',
                            'files',
                            'progressions' => function($query) use ($user) {
                                $query->where('user_id', $user->id);
                            }
                        ])
                        ->orderBy('order', 'asc')
                        ->get();

        $normalizedFolders = [];
        $sessionsByFolderID = [];

        /** @var Session $session */
        foreach ($sessions as $session) {
            $folderID = Folder::DEFAULT_FOLDER_ID;
            $folder = $session->folder;
            $files = $session->files;

            //Set folder ID if there is a folder
            if (filled($folder)) {
                $folderID = $folder->id;
            }

            //If no record of this folder ID in the normalizedFolders array, normalize it and add
            if (!isset($normalizedFolders[$folderID])) {
                $normalizedFolders[$folderID] = $this->folderNormalizer->normalize($folder);
            }

            //Normalize the session and put the data in sessionsByFolderID array
            $sessionsByFolderID[$folderID][] = $this->sessionNormalizer->normalize(
                $session,
                $files,
                filled($session->progressions)
            );
        }

        //Loop through and store all sessions in respectable normalizedFolders
        foreach ($normalizedFolders as $key => &$folder) {
            $folder['sessions'] = $sessionsByFolderID[$key];
        }

        //Sort folders by order before returning
        $this->sortFoldersByOrder($normalizedFolders);

        return [
            'hash_id' => Hashids::connection('course')->encode($course->id),
            'name' => $course->name,
            'description' => $course->description,
            'teacher' => $course->teacher,
            'created_at' => $course->created_at,
            'updated_at' => $course->updated_at,
            'folders' => $normalizedFolders,
        ];
    }

    /**
     * Sort folders by order
     *
     * @param array $folders
     */
    private function sortFoldersByOrder(array &$folders) {
        if(count($folders) > 1) {
            usort(
                $folders,
                function ($one, $other) {
                    return $one["order"] > $other["order"];
                }
            );
        }
    }
}
