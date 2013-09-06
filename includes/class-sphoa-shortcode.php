<?php
/**
 * Simple Photo Albums
 *
 * @package SimplePhotoAlbums
 * @author Brady Vercher <brady@audiotheme.com>
 * @copyright Copyright (c) 2013, AudioTheme, LLC
 * @license GPL-2.0+
 */

/**
 * Class to render a photo album from the shortcode.
 *
 * @package SimplePhotoAlbums
 * @author Brady Vercher <brady@audiotheme.com>
 * @since 1.0.0
 */
class Sphoa_Shortcode {
	/**
	 * List of all albums rendered on the current page.
	 *
	 * @access protected
	 * @var array
	 */
	protected $albums = array();

	/**
	 * List of galleries in the current album.
	 *
	 * @access protected
	 * @var array
	 */
	protected $galleries = array();

	/**
	 * List of gallery covers for the current album.
	 *
	 * @access protected
	 * @var array
	 */
	protected $gallery_covers = array();

	/**
	 * Internal counter for identifying gallery instances.
	 *
	 * @access protected
	 * @var int
	 */
	protected $gallery_instance = 0;

	/**
	 * Internal counter to keep covers synced with the galleries.
	 *
	 * @access protected
	 * @var int
	 */
	protected $cover_instance = 0;

	/**
	 * Toggle whether cover images should be included when galleries are displayed.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $exclude_covers = false;

	/**
	 * Retrieve the list of photo albums.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_albums() {
		return $this->albums;
	}

	/**
	 * Process an album shortcode.
	 *
	 * Any galleries within the shortcode are considerd an album. The first
	 * image of the gallery is used as the cover, or one can be explicitly
	 * declared using a "cover" attribute on each gallery shortcode.
	 *
	 * Display related attributes that can be used with a gallery shortcode can
	 * also be used on the albums shortcode.
	 *
	 * Shortcode attributes:
	 *
	 * - exclude_covers - If a cover image isn't defined on a gallery shortcode,
	 *     the first image is used. Setting this option will remove it from
	 *     the gallery.
	 *
	 * New gallery shortcode attributes:
	 *
	 * - cover - The id of an image to use as the album cover.
	 * - album_link - URL where the cover image should link to if JavaScript
	 *       fails to display the gallery.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes.
	 * @param string $content Content included in the shortcode.
	 * @return string
	 */
	public function shortcode( $atts, $content = '' ) {
		$this->galleries = array();
		$this->gallery_covers = array();

		do_action( 'simple_photo_albums_shortcode_before' );

		if ( ! empty( $atts['exclude_covers'] ) ) {
			$this->exclude_covers = $this->shortcode_bool( $atts['exclude_covers'] );
		}

		add_filter( 'post_gallery', array( $this, '_get_album_data' ), 1, 2 );
		do_shortcode( $content );
		remove_filter( 'post_gallery', array( $this, '_get_album_data' ), 1, 2 );

		// Build list of attributes to pass from the [simple_photo_album] shortcode to [gallery].
		$args = shortcode_atts(
			array(
				'itemtag'    => 'dl',
				'icontag'    => 'dt',
				'captiontag' => 'dd',
				'columns'    => 3,
				'size'       => 'thumbnail',
			),
			$atts
		);

		$args['link'] = 'file';

		// Allow extensions to override the output.
		$content = apply_filters( 'simple_photo_albums_output', '', $this->galleries, $this->gallery_covers, $args, $atts );

		// Render the album if the filter doesn't return any output.
		if ( empty( $content ) ) {
			$content = $this->render( $args );
		}

		do_action( 'simple_photo_albums_shortcode_after' );

		return $content;
	}

	/**
	 * Render an album.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Attributes to pass to the [gallery] shortcode.
	 * @return string
	 */
	protected function render( $atts ) {
		// Generate a new gallery shortcode to display the albums.
		$pairs[] = 'ids="' . implode( ',', $this->gallery_covers ) . '"';

		foreach ( $atts as $key => $value ) {
			$pairs[] = $key . '="' . $value . '"';
		}

		$content  = '<div class="simple-photo-albums">';
		$content .= '[gallery ' . implode( ' ', $pairs ) . ']';
		$content .= '</div>';

		// Render the gallery of album covers.
		add_filter( 'wp_get_attachment_link', array( $this, '_add_album_data' ), 10, 2 );
		$content = do_shortcode( $content );
		remove_filter( 'wp_get_attachment_link', array( $this, '_add_album_data' ), 10, 2 );

		return $content;
	}

	/**
	 * Gallery shortcode filter.
	 *
	 * Filter the gallery shortcode to determine which attachments should be
	 * included, the cover image, and where the album should link to if
	 * JavaScript isn't available.
	 *
	 * Return a space to prevent the gallery from being rendered or generating
	 * additional queries.
	 *
	 * @since 1.0.0
	 * @see gallery_shortcode()
	 *
	 * @param string $output Gallery output.
	 * @param array $atts Gallery shortcode attributes.
	 * @return string
	 */
	public function _get_album_data( $output, $atts ) {
		$post = get_post();
		$this->gallery_instance ++;

		// Grab custom attributes before they're removed.
		$cover_id     = ( empty( $atts['cover'] ) ) ? null : $atts['cover'];
		$gallery_link = ( empty( $atts['gallery_link'] ) ) ? '' : $atts['gallery_link'];

		// Determine which attachments should be included in the album.
		// Essentially follows the logic in the core gallery shortcode callback.
		if ( isset( $attr['orderby'] ) ) {
			$atts['orderby'] = sanitize_sql_orderby( $atts['orderby'] );
			if ( ! $atts['orderby'] ) {
				unset( $atts['orderby'] );
			}
		}

		$atts = shortcode_atts(
			array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'ids'        => '',
				'include'    => '',
				'exclude'    => '',
			),
			$atts
		);

		$atts['id'] = absint( $atts['id'] );
		if ( 'RAND' == $atts['order'] ) {
			$atts['orderby'] = 'none';
		}

		// Build up an array of arguments to pass to get_posts().
		$args = array(
			'post_parent'    => $atts['id'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby'],
			'numberposts'    => -1,
			'fields'         => 'ids',
		);

		if ( ! empty( $atts['ids'] ) ) {
			$atts['include'] = $atts['ids'];

			// 'ids' should be explicitly ordered.
			$args['orderby'] = 'post__in';
		}

		if ( empty( $atts['include'] ) && ! empty( $atts['exclude'] ) ) {
			$args['exclude'] = $atts['exclude'];
		}

		if ( ! empty( $atts['include'] ) ) {
			$ids = wp_parse_id_list( $atts['include'] );
		} else {
			$ids = get_posts( $args );
		}

		// Save a reference to the attachments.
		if ( ! empty( $ids ) ) {
			$gallery['post_id'] = $post->ID;
			$gallery['cover']   = ( empty( $cover_id ) ) ? $ids[0] : absint( $cover_id );
			$gallery['link']    = $gallery_link;

			// Remove the cover from the list of ids.
			// Only removes the cover if one hasn't been manually defined.
			// Otherwise, the cover id just shouldn't be added to the list of gallery attachments.
			if ( $this->exclude_covers && empty( $cover_id ) ) {
				array_shift( $ids );
			}

			$gallery['attachment_ids'] = $ids;

			$this->galleries[] = $gallery;
			$this->gallery_covers[] = $gallery['cover'];
			$this->albums[ $this->gallery_instance ] = $gallery;
		}

		// Short-circuit the gallery output.
		return ' ';
	}

	/**
	 * Add data about the album to the album's output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html Default album cover output.
	 * @param int $attachment_id Album cover ID.
	 * @return string
	 */
	public function _add_album_data( $html, $attachment_id ) {
		$post = get_post();
		$this->cover_instance ++;

		if ( empty( $this->albums[ $this->cover_instance ] ) ) {
			return $html;
		}

		$gallery = $this->albums[ $this->cover_instance ];

		// Insert a data attribute with a reference to the gallery instance id.
		$html = str_replace( '<a ', sprintf( '<a data-album-id="album-%d" data-post-id="post-%d" ', $this->cover_instance, $post->ID ), $html );

		// Replace the gallery link.
		if ( ! empty( $gallery['link'] ) ) {
			$html = preg_replace( "/href='[^']+'/", "href='" . esc_url( $gallery['link'] ) . "'", $html );
		}

		return $html;
	}

	/**
	 * Helper method to determine if a shortcode attribute is true or false.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int|bool $var Attribute value.
	 * @return bool
	 */
	protected function shortcode_bool( $var ) {
		$falsey = array( 'false', '0', 'no', 'n' );
		return ( ! $var || in_array( strtolower( $var ), $falsey ) ) ? false : true;
	}
}
