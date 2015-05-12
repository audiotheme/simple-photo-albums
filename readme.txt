=== Simple Photo Albums ===
Contributors: audiotheme, bradyvercher
Tags: photos, albums, gallery
Requires at least: 3.5
Tested up to: 4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create photo albums from a group of native galleries.

== Description ==

Simple Photo Albums turns a group of native gallery shortcodes into photo albums with cover images and allows for advanced integration with scripts like Jetpack's Carousel or Swipebox.

= Usage =

Wrap a group of `[gallery]` shortcodes with the `[simple_photo_album]` shortcode:

`[simple_photo_album]
[gallery ids="1,2,3"]
[gallery ids="4,5,6"]
[gallery ids="7,8,9"]
[/simple_photo_album]`

Each gallery becomes an album and requires clicking through to see the actual gallery itself. This example will render the same as `[gallery ids="1,4,7"]`, which admittedly isn't very useful.

However, there are a few attributes that may be beneficial and things really shine when paired with third-party scripts like [Jetpack's Carousel](http://jetpack.me/support/carousel/), [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/), or [Swipebox](http://brutaldesign.github.io/swipebox/) -- native support is included for all (select one on the **Settings &rarr; Media** screen).

Galleries within the album shortcode may include a `gallery_link` attribute to link the cover image to an arbitrary permalink when a third-party script isn't being used:

`[gallery ids="1,2,3" gallery_link="http://example.com/gallery-one/"]`

= Cover Images =

By default the first image in each gallery shortcode is used as its cover image and will be included when the gallery is viewed. A manually defined cover can be passed as an additional attribute:

`[gallery ids="1,2,3" cover="25"]`

Covers can be removed from the galleries entirely using the `exclude_covers` attribute on the album shortcode:

`[simple_photo_album exclude_covers="1"]`

= Album Display =

Any display-related attributes that can be used on native gallery shortcodes can also be used on the album shortcode to modify its appearance (itemtag, icontag, captiontag, columns, size).

`[simple_photo_album columns="4"]`

= Additional Resources =

* [Check out AudioTheme](https://audiotheme.com/?utm_source=wordpress.org&utm_medium=link&utm_content=simple-photo-albums-readme&utm_campaign=plugins) and tell your friends!
* Help out on the [support forums](https://wordpress.org/support/plugin/simple-photo-albums).
* Consider [contributing on GitHub](https://github.com/audiotheme/simple-photo-albums)
* [Write a review](http://wordpress.org/support/view/plugin-reviews/simple-photo-albums#postform)
* [Follow @AudioTheme](https://twitter.com/AudioTheme)

= Translation Credits =

* French (fr_FR) - Gwenhael Le Mansec [v1.1.1]

== Installation ==

Installing Simple Photo Albums is just like installing most other plugins. [Check out the codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins) if you have any questions.

== Frequently Asked Questions ==

= Can support be added for additional third-party scripts? =
Yes. Find the Jetpack Carousel, Magnific Popup and Swipebox files in the plugin folder as an example. They're all written as plugins to demonstrate how support can be added for just about any script.

== Changelog ==

= 1.1.1 =
* Added French translation.

= 1.1.0 =
* Added support for Magnific Popup.

= 1.0.3 =
* Load the plugin text domain on 'plugins_loaded' in preparation for language packs.

= 1.0.2 =
* Try to remove existing lightbox scripts attached to Swipebox albums.
* Load the Jetpack gallery script later so it's properly initialized.
* Sort the gallery scripts alphabetically in the settings dropdown.

= 1.0.1 =
* Fix captions in the Swipebox extension.
* Fix strict PHP notices.

= 1.0 =
* Initial release.
