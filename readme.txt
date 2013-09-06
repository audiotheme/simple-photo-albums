=== Simple Photo Albums ===
Contributors: audiotheme, bradyvercher
Tags: photos, albums, gallery
Requires at least: 3.5
Tested up to: 3.6
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

However, there are a few attributes that may be beneficial and things really shine when paired with third-party scripts like [Jetpack's Carousel](http://jetpack.me/support/carousel/) or [Swipebox](http://brutaldesign.github.io/swipebox/) -- native support is included for both (select one on the **Media &rarr; Settings** screen).

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

* [Write a review](http://wordpress.org/support/view/plugin-reviews/simple-photo-albums#postform)
* [Have a question?](http://wordpress.org/support/plugin/simple-photo-albums)
* [Contribute on GitHub](https://github.com/audiotheme/simple-photo-albums)
* [Follow @audiotheme](https://twitter.com/AudioTheme)
* [Visit AudioTheme](http://audiotheme.com/)

== Installation ==

Installing Simple Photo Albums is just like installing most other plugins. [Check out the codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins) if you have any questions.

== Frequently Asked Questions ==

= Can support be added for additional third-party scripts? =
Yes. Find the Jetpack Carousel and Swipebox files in the plugin folder as an example. Both are written as plugins to demonstrate how support can be added for just about any script.

== Changelog ==

= 1.0 =
* Initial release.