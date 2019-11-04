=== Simple WebP ===
Contributors: jillro
Tags: webp, performance
Requires at least: 4.7.1
Tested up to: 5.2
Stable tag: 1.0.0
Requires PHP: 5.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

For every image uploaded, Simple WebP creates a WebP version which is served to compatible browsers.

== Description ==

Every time you upload an image, Simple WebP generates a .webp version. It then alter the HTML to replace img tags
with picture tags, which let the browser pick the best format.

WebP images are often smaller than original .jpeg or .png one, so can save bandwidth and load time. Most browsers
support WebP nowadays. You can use [Google Pagespeed](https://developers.google.com/speed/pagespeed/insights/)
tool to see the gain you would get from WebP.

You will get much faster load time, and better ranking in

Simple WebP is very much inspired by the more complete (and complex to install)
[WebP Express](https://fr.wordpress.org/plugins/webp-express/) plugin. It also use the same libraries :
* [WebPConvert](https://github.com/rosell-dk/webp-convert): For converting images to webp
* [DOM Util for WebP](https://github.com/rosell-dk/dom-util-for-webp): For the Alter HTML functionality

== Frequently Asked Questions ==

= Are there any settings ? =

No, just activate the plugin and enjoy the effects. If you want more settings, use
[WebP Express](https://fr.wordpress.org/plugins/webp-express/).

= What if the WebP version is actually bigger than the original ? =

Yes, it happens. If after the conversion, the .webp version is bigger, it is immediately deleted so it will not be
served.

= Is it compatible with browser not compatible with picture tag ? =

Yes, a polyfill is included.

= Will it work for previously uploaded images ? =

Simple WebP generates .webp version of images on upload. So your previously uploaded images will not be converted.

However, Simple WebP provide a [WP-CLI](https://wp-cli.org/fr/) command to bulk convert all your uploaded image
on your server : `wp-cli webp-simple-bulk-convert`.

= Does it work for theme images ? =

No, it works only with uploaded images. Converting theme image requires to set up on demand converting, which is
way more complex, and often needs to do manual configuration on the server. See
[WebP Express](https://fr.wordpress.org/plugins/webp-express/).

= Does it work with Nginx ? =

Yes, it works out of the box with Nginx as long as you serve directly static files.

= Does it work on Windows servers ? =

Maybe. Probably not.

= Will it delete .webp images from my server if I delete the plugin ? =

No. But they will not be served anymore.
