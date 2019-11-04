<?php


namespace WebPSimple;

use DOMUtilForWebP\PictureTags as BasePictureTags;

class PictureTags extends BasePictureTags
{
    public function replaceUrl($url)
    {
        $dir  = wp_get_upload_dir();

        $site_url = parse_url($dir['url']);
        $image_path = parse_url($url);

        // force the protocols to match if needed
        if (isset($image_path['scheme']) && ($image_path['scheme'] !== $site_url['scheme'])) {
            $url = str_replace($image_path['scheme'], $site_url['scheme'], $url);
        }

        // check if this is an uploaded image
        if (0 !== strpos($url, $dir['baseurl'] . '/')) {
            return false;
        }
        $filename = path_join($dir["basedir"], str_replace($dir['baseurl'].'/', '', $url));
        if (!@file_exists($filename.".webp")) {
            return false;
        }

        return parent::replaceUrl($url);
    }
}
