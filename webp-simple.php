<?php
/**
 * Plugin Name: Simple WebP
 * Plugin URI: https://github.com/jillro/webp-simple
 * Description: Serve WebP images instead of jpeg/png to browsers that supports WebP.
 * Version: 1.0.0
 * Author: Jill Royer
 * Author URI: https://jillroyer.me
 * License: GPL3
 */


if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


define('WEBPSIMPLE_PLUGIN', __FILE__);
define('WEBPSIMPLE_PLUGIN_DIR', __DIR__);

require __DIR__ . '/vendor/autoload.php';

use \WebPSimple\PictureTags;

// When images are uploaded with Gutenberg, is_admin() returns false, so, hook needs to be added here
add_filter('wp_handle_upload', array('WebPSimple\HandleUploadHooks', 'handleUpload'), 10, 2);
add_filter('image_make_intermediate_size', array('WebPSimple\HandleUploadHooks', 'handleMakeIntermediateSize'), 10, 1);
add_filter('wp_delete_file', array('WebPSimple\HandleUploadHooks', 'deleteAssociatedWebP'), 10, 2);


function webPSimpleAddPictureJs()
{
    // Don't do anything with the RSS feed.
    // - and no need for PictureJs in the admin
    if (is_feed() || is_admin()) {
        return;
    }

    echo '<script>'
       . 'document.createElement( "picture" );'
       . 'if(!window.HTMLPictureElement && document.addEventListener) {'
            . 'window.addEventListener("DOMContentLoaded", function() {'
                . 'var s = document.createElement("script");'
                . 's.src = "' . plugins_url('/js/picturefill.min.js', __FILE__) . '";'
                . 'document.body.appendChild(s);'
            . '});'
        . '}'
       . '</script>';
}

add_action('wp_head', 'webPSimpleAddPictureJs');

function webPSimpleAlterHtml($content)
{
    // Don't do anything with the RSS feed.
    if (is_feed()) {
        return $content;
    }

    if (is_admin()) {
        return $content;
    }

    // Exit if it doesn't look like HTML (see #228)
    if (!preg_match("#^\\s*<#", $content)) {
        return $content;
    }

    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $content;
    }

    return PictureTags::replace($content);
}

// priority big, so it will be executed last
add_filter('the_content', 'webPSimpleAlterHtml', 10000);
add_filter('the_excerpt', 'webPSimpleAlterHtml', 10000);
add_filter('post_thumbnail_html', 'webPSimpleAlterHtml', 1000);

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('webp-simple-bulk-convert', array('WebPSimple\BulkConvertCommand', 'execute'));
}
