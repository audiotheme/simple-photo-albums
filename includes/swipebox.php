<?php
/**
 * Simple Photo Albums
 *
 * @package SimplePhotoAlbums\GalleryScript
 * @author Brady Vercher <brady@audiotheme.com>
 * @copyright Copyright (c) 2013, AudioTheme, LLC
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Simple Photo Albums (Swipebox)
 * Plugin URI: http://wordpress.org/plugins/simple-photo-albums/
 * Description: Swipebox support for Simple Photo Albums.
 * Version: 1.0.0
 * Author: AudioTheme
 * Author URI: http://audiotheme.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Swipebox support for Simple Photo Albums.
 *
 * Demonstrates how to extend the main plugin to integrate support for a
 * third-party gallery script.
 *
 * @package SimplePhotoAlbums\GalleryScript
 * @author Brady Vercher <brady@audiotheme.com>
 * @since 1.0.0
 */
class Sphoa_GalleryScript_Swipebox {
	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_filter( 'simple_photo_albums_gallery_scripts', array( __CLASS__, 'register_addon' ) );

		// Don't register if the this isn't the active script.
		$settings = sphoa()->get_settings();
		if ( 'swipebox' != $settings['gallery_script'] ) {
			return;
		}

		add_action( 'simple_photo_albums_shortcode_before', array( __CLASS__, 'register_assets' ) );
		add_action( 'simple_photo_albums_print_footer_scripts', array( __CLASS__, 'print_script' ) );
	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function register_addon( $scripts ) {
		$scripts['swipebox'] = __( 'Swipebox', 'simple-photo-albums' );
		return $scripts;
	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function register_assets() {
		wp_enqueue_script( 'simple-photo-albums-swipebox', plugin_dir_url( __FILE__ ) . 'libraries/swipebox/jquery.swipebox.min.js', array( 'jquery' ), '1.2.1', true );
		wp_enqueue_style( 'simple-photo-albums-swipebox', plugin_dir_url( __FILE__ ) . 'libraries/swipebox/swipebox.css' );
	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function print_script( $albums ) {
		// Build an array for output as a JavaScript object.
		$data = array();
		foreach ( $albums as $gallery_id => $gallery ) {
			foreach ( $gallery['attachment_ids'] as $id ) {
				$attachment = get_post( $id );

				$data[ 'album-' . $gallery_id ][] = array(
					'href'     => wp_get_attachment_url( $id ),
					'caption' => wptexturize( $attachment->post_excerpt ),
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
		jQuery(function($) {
			$('.gallery').on('click', 'a[data-album-id]', function(e) {
				e.preventDefault();
				$.swipebox( simplePhotoAlbums[ $(this).data('album-id') ] );
			});
		});
		</script>
		<?php
	}
}
add_action( 'plugins_loaded', array( 'Sphoa_GalleryScript_Swipebox', 'load' ) );
