<?php

use Illuminate\Support\Facades\File;

if (!function_exists('emptyDirectories')) {
    /**
     * @param array $directories
     */
    function emptyDirectories($directories)
    {

        if (is_array($directories)) {
            foreach ($directories as $directory) {
                if (is_dir($directory)) {
                    File::deleteDirectory($directory);
                }
            }
        } else {
            if (is_dir($directories)) {
                File::deleteDirectory($directories);
            }
        }

    }
}