<?php
/**
 * Simple Photo Albums
 *
 * @package SimplePhotoAlbums\GalleryScript
 * @author Brady Vercher <brady@audiotheme.com>
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Simple Photo Albums (Magnific Popup)
 * Plugin URI: http://wordpress.org/plugins/simple-photo-albums/
 * Description: Magnific Popup support for Simple Photo Albums.
 * Version: 1.0.1
 * Author: AudioTheme
 * Author URI: https://audiotheme.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Magnific Popup support for Simple Photo Albums.
 *
 * Demonstrates how to extend the main plugin to integrate support for a
 * third-party gallery script.
 *
 * @package SimplePhotoAlbums\GalleryScript
 * @author Brady Vercher <brady@audiotheme.com>
 * @since 1.0.0
 */
class Sphoa_GalleryScript_MagnificPopup {
	/**
	 * Load Magnific Popup extension.
	 *
	 * @since 1.0.0
	 */
	public static function load() {
		add_filter( 'simple_photo_albums_gallery_scripts', array( __CLASS__, 'register_addon' ) );

		// Don't register if the this isn't the active script.
		$settings = sphoa()->get_settings();
		if ( 'magnific-popup' != $settings['gallery_script'] ) {
			return;
		}

		add_action( 'simple_photo_albums_shortcode_before', array( __CLASS__, 'register_assets' ) );
		add_action( 'simple_photo_albums_print_footer_scripts', array( __CLASS__, 'print_script' ) );
	}

	/**
	 * Register the Magnific Popup gallery script.
	 *
	 * @since 1.0.0
	 *
	 * @param array $scripts List of gallery scripts.
	 * @return array
	 */
	public static function register_addon( $scripts ) {
		$scripts['magnific-popup'] = __( 'Magnific Popup', 'simple-photo-albums' );
		return $scripts;
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public static function register_assets() {
		wp_enqueue_script( 'simple-photo-albums-magnific-popup', plugin_dir_url( __FILE__ ) . 'libraries/magnific-popup/jquery.magnific-popup.js', array( 'jquery' ), '1.1.0', true );
		wp_enqueue_style( 'simple-photo-albums-magnific-popup', plugin_dir_url( __FILE__ ) . 'libraries/magnific-popup/magnific-popup.css' );
	}

	/**
	 * Print scripts for integrating albums with Magnific Popup.
	 *
	 * @since 1.0.0
	 */
	public static function print_script( $albums ) {
		// Build an array for output as a JavaScript object.
		$data = array();
		foreach ( $albums as $gallery_id => $gallery ) {
			foreach ( $gallery['attachment_ids'] as $id ) {
				$attachment = get_post( $id );

				$data[ 'album-' . $gallery_id ][] = array(
					'src'   => wp_get_attachment_url( $id ),
					'title' => wptexturize( $attachment->post_excerpt ),
				);
			}
		}

		// Print a JavaScript object.
		echo "<script type='text/javascript'>\n";
		echo "/* <![CDATA[ */\n";
		echo "var simplePhotoAlbums = " . json_encode( $data ) . ";\n";
		echo "/* ]]> */\n";
		echo "</script>\n";
		?>
		<script type="text/javascript">
		jQuery(function( $ ) {
			// Try to remove existing handlers.
			$( '.simple-photo-albums .gallery a' ).off( 'click' ).removeClass( 'cboxElement' );

			$( '.gallery' ).on( 'click', 'a[data-album-id]', function( e ) {
				e.preventDefault();

				$.magnificPopup.open({
					items: simplePhotoAlbums[ $( this ).data( 'album-id' ) ],
					gallery: {
						enabled: true
					},
					type: 'image'
				});
			});
		});
		</script>
		<?php
	}
}
add_action( 'plugins_loaded', array( 'Sphoa_GalleryScript_MagnificPopup', 'load' ) );
