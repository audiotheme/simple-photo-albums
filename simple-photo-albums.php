<?php
/**
 * Simple Photo Albums
 *
 * @package SimplePhotoAlbums
 * @author Brady Vercher <brady@audiotheme.com>
 * @copyright Copyright (c) 2013, AudioTheme, LLC
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Simple Photo Albums
 * Plugin URI: http://wordpress.org/plugins/simple-photo-albums/
 * Description: A shortcode for creating photo albums from a group of galleries.
 * Version: 1.1.1
 * Author: AudioTheme
 * Author URI: http://audiotheme.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-photo-albums
 * Domain Path: /languages
 */

/**
 * Main plugin class.
 *
 * Sphoa = (S)imple (Pho)to (A)lbums
 *
 * @package SimplePhotoAlbums
 * @author Brady Vercher <brady@audiotheme.com>
 * @since 1.0.0
 */
class Sphoa {
	/**
	 * @access private
	 * @var Simple_Photo_Albums
	 */
	private static $instance;

	/**
	 * Object to handle rendering of the photo albums.
	 *
	 * @access private
	 * @var Sphoa_Shortcode
	 */
	private $shortcode;

	/**
	 * Main Simple_Photo_Albums instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Simple_Photo_Albums
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor with initial setup.
	 *
	 * @access private
	 * @since 1.0.0
	 * @see Simple_Photo_Albums::instance();
	 */
	private function __construct() {
		$this->includes();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Retrieve a list of registered gallery scripts.
	 *
	 * Extensions should append an item to this array to add an option to the
	 * 'Gallery Script' dropdown on the media settings screen. Array keys will
	 * be used as the option/setting value, while the array value should be the
	 * name of the gallery script.
	 *
	 * <code>array( 'script-id' => 'Script Name );</code>
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_gallery_scripts() {
		return apply_filters( 'simple_photo_albums_gallery_scripts', array() );
	}

	/**
	 * Retrieve the plugin settings.
	 *
	 * @since 1.0.0
	 */
	public function get_settings() {
		$settings = wp_parse_args(
			(array) get_option( 'simple_photo_albums' ),
			array(
				'gallery_script' => '',
			)
		);

		return $settings;
	}

	/**
	 * Set the instance of the shortcode class to handle rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param object $shortcode
	 */
	public function set_shortcode( $shortcode ) {
		$this->shortcode = $shortcode;
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	private function includes() {
		// Include the shortcode class.
		require( plugin_dir_path( __FILE__ ) . 'includes/class-sphoa-shortcode.php' );

		// Load default gallery scripts.
		include( plugin_dir_path( __FILE__ ) . 'includes/jetpack-carousel.php' );
		include( plugin_dir_path( __FILE__ ) . 'includes/magnific-popup.php' );
		include( plugin_dir_path( __FILE__ ) . 'includes/swipebox.php' );

		if ( is_admin() ) {
			require( plugin_dir_path( __FILE__ ) . 'admin/includes/settings.php' );
		}
	}

	/**
	 * Support localization for the plugin strings.
	 *
	 * @link http://ottopress.com/2013/language-packs-101-prepwork/
	 * @link http://www.geertdedeckere.be/article/loading-wordpress-language-files-the-right-way
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'simple-photo-albums', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Set up plugin scripts and hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_shortcode( 'simple_photo_album', array( $this->shortcode, 'shortcode' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'print_scripts' ) );
	}

	/**
	 * Print JavaScript to display the galleries.
	 *
	 * @since 1.0.0
	 */
	public function print_scripts() {
		$albums   = $this->shortcode->get_albums();
		$settings = $this->get_settings();

		// Short-circuit execution if there aren't any albums or a gallery script hasn't been selected.
		if ( empty( $albums ) || empty( $settings['gallery_script'] ) ) {
			return;
		}

		// Prime the cache.
		$ids = array();

		foreach ( $albums as $gallery_id => $gallery ) {
			if ( empty( $albums[ $gallery_id ]['attachment_ids'] ) ) {
				continue;
			}

			$ids = array_merge( $ids, $albums[ $gallery_id ]['attachment_ids'] );
		}

		get_posts(
			array(
				'post_type'      => 'attachment',
				'post__in'       => $ids,
				'posts_per_page' => -1,
			)
		);

		do_action( 'simple_photo_albums_print_footer_scripts', $albums );
	}
}

/**
 * Function to easily access the main plugin instance.
 *
 * @since 1.0.0
 *
 * @return Simple_Photo_Albums
 */
function sphoa() {
	return Sphoa::instance();
}

// Get 'er started.
sphoa();

// Set the class for handling the shortcode.
sphoa()->set_shortcode( new Sphoa_Shortcode() );
