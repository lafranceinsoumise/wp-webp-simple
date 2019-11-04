<?php


namespace WebPSimple;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\WebPConvert;

class BulkConvertCommand
{

    /**
     * Generate the WebP version of all uploaded images.
     *     *
     * @when after_wp_load
     */
    public static function execute($args)
    {
        $dir  = wp_get_upload_dir();

        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir["basedir"]));
        $allFileCount = 0;
        $imageFileCount = 0;
        $missingImages = [];
        foreach ($rii as $file) {
            $allFileCount++;
            if ($file->isDir()){
                continue;
            }

            if (filesize($file->getPathName()) < 11 || !in_array(wp_get_image_mime($file->getPathName()), [
                'image/jpeg',
                'image/png',
            ])) {
                continue;
            }

            $imageFileCount++;

            if (@file_exists($file->getPathName().".webp")) {
                continue;
            }

            $missingImages[] = $file->getPathname();
        }

        echo "Found ".$allFileCount." files, ".$imageFileCount." image files, ".count($missingImages)." missing WebP version.\n";

        if (count($missingImages) == 0) {
            return;
        }
        echo "Lots of warning will probably be displayed : it's only WebPConvert trying different ways of converting.\n";

        foreach ($missingImages as $filename) {
            try {
                echo $filename."\n";
                WebPConvert::convert($filename, $filename . '.webp');
            } catch (ConversionFailedException $e) {
                error_log('Simple WebP failed converting: ' . $filename);
            }
        }
    }
}
