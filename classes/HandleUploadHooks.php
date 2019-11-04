<?php

namespace WebPSimple;

use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\WebPConvert;

class HandleUploadHooks
{
    /**
     *  hook: handle_upload
     */
    public static function handleUpload($filearray, $overrides = false, $ignore = false)
    {
        if (!isset($filearray['file'])) {
            return $filearray;
        }


        if (!in_array(wp_get_image_mime($filearray['file']), [
            'image/jpeg',
            'image/png',
        ])) {
            return $filearray;
        }

        $filename = $filearray["file"];

        try {
            WebPConvert::convert($filename, $filename . '.webp');
        } catch (ConversionFailedException $e) {
            error_log('Simple WebP failed converting: '.$filename);
        }

        if ($filename.'.webp' && filesize($filename).".webp" > filesize($filename)) {
            @unlink($filename.'.webp');
        }

        return $filearray;
    }

    /**
     *  hook: wp_delete_file
     */
    public static function deleteAssociatedWebP($filename)
    {
        if (!in_array(wp_get_image_mime($filename), [
            'image/jpeg',
            'image/png',
        ])) {
            return $filename;
        }

        $destination = $filename.'.webp';
        if (@file_exists($destination)) {
            if (!@unlink($destination)) {
                error_log('Simple WebP failed deleting webp: '.$destination);
            }
        }

        return $filename;
    }

    /**
     *  hook: image_make_intermediate_size
     */
    public static function handleMakeIntermediateSize($filename)
    {
        if (!in_array(wp_get_image_mime($filename), [
            'image/jpeg',
            'image/png',
        ])) {
            return $filename;
        }

        try {
            WebPConvert::convert($filename, $filename.'.webp');
        } catch (ConversionFailedException $e) {
            error_log('Simple WebP failed converting: '.$filename);
        }

        return $filename;
    }
}
