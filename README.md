# Simple Photo Albums #

A WordPress plugin to create photo albums from a group of native galleries.

__Contributors:__ [Brady Vercher](https://github.com/bradyvercher)  
__Requires:__ 3.5  
__Tested up to:__ 3.8.1  
__License:__ [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)

## Usage ##

Wrap a group of `[gallery]` shortcodes with the `[simple_photo_album]` shortcode:

```
[simple_photo_album]
[gallery ids="1,2,3"]
[gallery ids="4,5,6"]
[gallery ids="7,8,9"]
[/simple_photo_album]
```

Each gallery becomes an album and requires clicking through to see the actual gallery itself. This example will render the same as `[gallery ids="1,4,7"]`, which admittedly isn't very useful.

However, there are a few attributes that may be beneficial and things really shine when paired with third-party scripts like [Jetpack's Carousel](http://jetpack.me/support/carousel/), [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/), or [Swipebox](http://brutaldesign.github.io/swipebox/) -- native support is included for all (select one on the **Media &rarr; Settings** screen).

Galleries within the album shortcode may include a `gallery_link` attribute to link the cover image to an arbitrary permalink when a third-party script isn't being used:

`[gallery ids="1,2,3" gallery_link="http://example.com/gallery-one/"]`

### Cover Images

By default the first image in each gallery shortcode is used as its cover image and will be included when the gallery is viewed. A manually defined cover can be passed as an additional attribute:

`[gallery ids="1,2,3" cover="25"]`

Covers can be removed from the galleries entirely using the `exclude_covers` attribute on the album shortcode:

`[simple_photo_album exclude_covers="1"]`

### Album Display

Any display-related attributes that can be used on native gallery shortcodes can also be used on the album shortcode to modify its appearance (itemtag, icontag, captiontag, columns, size).

`[simple_photo_album columns="4"]`

### Shortcode Attributes

<table><caption><h4>[simple_photo_album]</strong></h4>
  <thead>
    <tr>
      <th>Attribute</th>
    <th>Description</th>
      <th>Example</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong><code>exclude_covers</code></strong></td>
      <td>A true or false value.</td>
	  <td><em><code>1</code></td>
    </tr>
  </tbody>
</table>

<table><caption><h4>[gallery]</strong></h4>
  <thead>
    <tr>
      <th>Attribute</th>
    <th>Description</th>
      <th>Example</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong><code>cover</code></strong></td>
      <td>An attachment ID.</td>
	  <td><em><code>95</code></td>
    </tr>
    <tr>
      <td><strong><code>gallery_link</code></strong></td>
      <td>Absolute URL to the gallery permalink if a script isn't being used.</td>
      <td><em><code>http://example.com/gallery-one/</code></em></td>
    </tr>
  </tbody>
</table>

## Credits ##

Built by [Brady Vercher](https://twitter.com/bradyvercher)
