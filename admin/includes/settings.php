<?php
/**
 * Plugin settings.
 *
 * @package SimplePhotoAlbums\Settings
 * @author Brady Vercher <brady@audiotheme.com>
 * @copyright Copyright (c) 2013, AudioTheme, LLC
 * @license GPL-2.0+
 */

/**
 * Register plugin settings.
 *
 * @since 1.0.0
 */
function sphoa_register_settings() {
	register_setting( 'media', 'simple_photo_albums' );

	add_settings_section(
		'simple-photo-albums',
		__( 'Simple Photo Albums', 'simple-photo-albums' ),
		'__return_null',
		'media'
	);

	add_settings_field(
		'gallery_script',
		__( 'Gallery Script', 'simple-photo-albums' ),
		'sphoa_gallery_script_field',
		'media',
		'simple-photo-albums'
	);
}
add_action( 'admin_init', 'sphoa_register_settings' );

/**
 * Display dropdown for selecting a gallery script.
 *
 * @since 1.0.0
 */
function sphoa_gallery_script_field() {
	$scripts  = sphoa()->get_gallery_scripts();
	$settings = sphoa()->get_settings();
	?>
	<select name="simple_photo_albums[gallery_script]" id="simple-photo-albums-gallery-script">
		<option value=""></option>
		<?php
		foreach ( $scripts as $key => $name ) {
			printf(
				'<option value="%s"%s>%s</option>',
				esc_attr( $key ),
				selected( $key, $settings['gallery_script'], false ),
				esc_html( $name )
			);
		}
		?>
	</select>
	<?php
}
