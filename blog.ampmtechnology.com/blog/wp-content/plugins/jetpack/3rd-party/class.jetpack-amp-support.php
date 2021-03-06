<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

use Automattic\Jetpack\Sync\Functions;

/**
 * Manages compatibility with the amp-wp plugin
 *
 * @see https://github.com/Automattic/amp-wp
 */
class Jetpack_AMP_Support {

	/**
	 * Apply custom AMP changes on the front-end.
	 */
	public static function init() {

		// Add Stats tracking pixel on Jetpack sites when the Stats module is active.
		if (
			Jetpack::is_module_active( 'stats' )
			&& ! ( defined( 'IS_WPCOM' ) && IS_WPCOM )
		) {
			add_action( 'amp_post_template_footer', array( 'Jetpack_AMP_Support', 'add_stats_pixel' ) );
		}

		/**
		 * Remove this during the init hook in case users have enabled it during
		 * the after_setup_theme hook, which triggers before init.
		 */
		remove_theme_support( 'jetpack-devicepx' );

		// Sharing.
		add_filter( 'jetpack_sharing_display_markup', array( 'Jetpack_AMP_Support', 'render_sharing_html' ), 10, 2 );
		add_filter( 'sharing_enqueue_scripts', array( 'Jetpack_AMP_Support', 'amp_disable_sharedaddy_css' ) );

		// enforce freedom mode for videopress.
		add_filter( 'videopress_shortcode_options', array( 'Jetpack_AMP_Support', 'videopress_enable_freedom_mode' ) );

		// include Jetpack og tags when rendering native AMP head.
		add_action( 'amp_post_template_head', array( 'Jetpack_AMP_Support', 'amp_post_jetpack_og_tags' ) );

		// Post rendering changes for legacy AMP.
		add_action( 'pre_amp_render_post', array( 'Jetpack_AMP_Support', 'amp_disable_the_content_filters' ) );

		// Add post template metadata for legacy AMP.
		add_filter( 'amp_post_template_metadata', array( 'Jetpack_AMP_Support', 'amp_post_template_metadata' ), 10, 2 );

		// Filter photon image args for AMP Stories.
		add_filter( 'jetpack_photon_post_image_args', array( 'Jetpack_AMP_Support', 'filter_photon_post_image_args_for_stories' ), 10, 2 );

		// Sync the amp-options.
		add_filter( 'jetpack_options_whitelist', array( 'Jetpack_AMP_Support', 'filter_jetpack_options_whitelist' ) );
	}

	/**
	 * Apply custom AMP changes in wp-admin.
	 */
	public static function admin_init() {
		// disable Likes metabox for post editor if AMP canonical disabled.
		add_filter( 'post_flair_disable', array( 'Jetpack_AMP_Support', 'is_amp_canonical' ), 99 );
	}

	/**
	 * Is the page in AMP 'canonical mode'.
	 * Used when themes register support for AMP with `add_theme_support( 'amp' )`.
	 *
	 * @return bool is_amp_canonical
	 */
	public static function is_amp_canonical() {
		return function_exists( 'amp_is_canonical' ) && amp_is_canonical();
	}

	/**
	 * Does the page return AMP content.
	 *
	 * @return bool $is_amp_request Are we on am AMP view.
	 */
	public static function is_amp_request() {
		$is_amp_request = ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() );

		/**
		 * Returns true if the current request should return valid AMP content.
		 *
		 * @since 6.2.0
		 *
		 * @param boolean $is_amp_request Is this request supposed to return valid AMP content?
		 */
		return apply_filters( 'jetpack_is_amp_request', $is_amp_request );
	}

	/**
	 * Remove content filters added by Jetpack.
	 */
	public static function amp_disable_the_content_filters() {
		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			add_filter( 'videopress_show_2015_player', '__return_true' );
			add_filter( 'protected_embeds_use_form_post', '__return_false' );
			remove_filter( 'the_title', 'widont' );
		}

		remove_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'filter' ), 11 );
		remove_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'maybe_create_links' ), 100 );
	}

	/**
	 * Add Jetpack stats pixel.
	 *
	 * @since 6.2.1
	 */
	public static function add_stats_pixel() {
		if ( ! has_action( 'wp_footer', 'stats_footer' ) ) {
			return;
		}
		stats_render_amp_footer( stats_build_view_data() );
	}

	/**
	 * Add publisher and image metadata to legacy AMP post.
	 *
	 * @since 6.2.0
	 *
	 * @param array   $metadata Metadata array.
	 * @param WP_Post $post     Post.
	 * @return array Modified metadata array.
	 */
	public static function amp_post_template_metadata( $metadata, $post ) {
		if ( isset( $metadata['publisher'] ) && ! isset( $metadata['publisher']['logo'] ) ) {
			$metadata = self::add_site_icon_to_metadata( $metadata );
		}

		if ( ! isset( $metadata['image'] ) ) {
			$metadata = self::add_image_to_metadata( $metadata, $post );
		}

		return $metadata;
	}

	/**
	 * Add blavatar to legacy AMP post metadata.
	 *
	 * @since 6.2.0
	 *
	 * @param array $metadata Metadata.
	 *
	 * @return array Metadata.
	 */
	private static function add_site_icon_to_metadata( $metadata ) {
		$size          = 60;
		$site_icon_url = class_exists( 'Automattic\\Jetpack\\Sync\\Functions' ) ? Functions::site_icon_url( $size ) : '';

		if ( function_exists( 'blavatar_domain' ) ) {
			$metadata['publisher']['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => blavatar_url( blavatar_domain( site_url() ), 'img', $size, self::staticize_subdomain( 'https://wordpress.com/i/favicons/apple-touch-icon-60x60.png' ) ),
				'width'  => $size,
				'height' => $size,
			);
		} elseif ( $site_icon_url ) {
			$metadata['publisher']['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $site_icon_url,
				'width'  => $size,
				'height' => $size,
			);
		}

		return $metadata;
	}

	/**
	 * Add image to legacy AMP post metadata.
	 *
	 * @since 6.2.0
	 *
	 * @param array   $metadata Metadata.
	 * @param WP_Post $post     Post.
	 * @return array Metadata.
	 */
	private static function add_image_to_metadata( $metadata, $post ) {
		$image = Jetpack_PostImages::get_image(
			$post->ID,
			array(
				'fallback_to_avatars' => true,
				'avatar_size'         => 200,
				// AMP already attempts these.
				'from_thumbnail'      => false,
				'from_attachment'     => false,
			)
		);

		if ( empty( $image ) ) {
			return self::add_fallback_image_to_metadata( $metadata );
		}

		if ( ! isset( $image['src_width'] ) ) {
			$dimensions = self::extract_image_dimensions_from_getimagesize(
				array(
					$image['src'] => false,
				)
			);

			if ( false !== $dimensions[ $image['src'] ] ) {
				$image['src_width']  = $dimensions['width'];
				$image['src_height'] = $dimensions['height'];
			}
		}

		$metadata['image'] = array(
			'@type' => 'ImageObject',
			'url'   => $image['src'],
		);
		if ( isset( $image['src_width'] ) ) {
			$metadata['image']['width'] = $image['src_width'];
		}
		if ( isset( $image['src_width'] ) ) {
			$metadata['image']['height'] = $image['src_height'];
		}

		return $metadata;
	}

	/**
	 * Add fallback image to legacy AMP post metadata.
	 *
	 * @since 6.2.0
	 *
	 * @param array $metadata Metadata.
	 * @return array Metadata.
	 */
	private static function add_fallback_image_to_metadata( $metadata ) {
		/** This filter is documented in functions.opengraph.php */
		$default_image = apply_filters( 'jetpack_open_graph_image_default', 'https://wordpress.com/i/blank.jpg' );

		$metadata['image'] = array(
			'@type'  => 'ImageObject',
			'url'    => self::staticize_subdomain( $default_image ),
			'width'  => 200,
			'height' => 200,
		);

		return $metadata;
	}

	/**
	 * Return static WordPress.com domain to use to load resources from WordPress.com.
	 *
	 * @param string $domain Asset URL.
	 */
	private static function staticize_subdomain( $domain ) {
		// deal with WPCOM vs Jetpack.
		if ( function_exists( 'staticize_subdomain' ) ) {
			return staticize_subdomain( $domain );
		} else {
			return Jetpack::staticize_subdomain( $domain );
		}
	}

	/**
	 * Extract image dimensions via wpcom/imagesize, only on WPCOM
	 *
	 * @since 6.2.0
	 *
	 * @param array $dimensions Dimensions.
	 * @return array Dimensions.
	 */
	private static function extract_image_dimensions_from_getimagesize( $dimensions ) {
		if ( ! ( defined( 'IS_WPCOM' ) && IS_WPCOM && function_exists( 'require_lib' ) ) ) {
			return $dimensions;
		}
		require_lib( 'wpcom/imagesize' );

		foreach ( $dimensions as $url => $value ) {
			if ( is_array( $value ) ) {
				continue;
			}
			$result = wpcom_getimagesize( $url );
			if ( is_array( $result ) ) {
				$dimensions[ $url ] = array(
					'width'  => $result[0],
					'height' => $result[1],
				);
			}
		}

		return $dimensions;
	}

	/**
	 * Display Open Graph Meta tags in AMP views.
	 */
	public static function amp_post_jetpack_og_tags() {
		if ( ! ( defined( 'IS_WPCOM' ) && IS_WPCOM ) ) {
			Jetpack::init()->check_open_graph();
		}

		if ( function_exists( 'jetpack_og_tags' ) ) {
			jetpack_og_tags();
		}
	}

	/**
	 * Force Freedom mode in VideoPress.
	 *
	 * @param array $options Array of VideoPress shortcode options.
	 */
	public static function videopress_enable_freedom_mode( $options ) {
		if ( self::is_amp_request() ) {
			$options['freedom'] = true;
		}
		return $options;
	}

	/**
	 * Display custom markup for the sharing buttons when in an AMP view.
	 *
	 * @param string $markup          Content markup of the Jetpack sharing links.
	 * @param array  $sharing_enabled Array of Sharing Services currently enabled.
	 */
	public static function render_sharing_html( $markup, $sharing_enabled ) {
		if ( ! self::is_amp_request() ) {
			return $markup;
		}

		remove_action( 'wp_footer', 'sharing_add_footer' );
		if ( empty( $sharing_enabled ) ) {
			return $markup;
		}
		$supported_services = array(
			'facebook'  => array(
				/** This filter is documented in modules/sharedaddy/sharing-sources.php */
				'data-param-app_id' => apply_filters( 'jetpack_sharing_facebook_app_id', '249643311490' ),
			),
			'twitter'   => array(),
			'pinterest' => array(),
			'whatsapp'  => array(),
			'tumblr'    => array(),
			'linkedin'  => array(),
		);
		$sharing_links      = array();
		foreach ( $sharing_enabled['visible'] as $id => $service ) {
			if ( ! isset( $supported_services[ $id ] ) ) {
				$sharing_links[] = "<!-- not supported: $id -->";
				continue;
			}
			$args         = array_merge(
				array(
					'type' => $id,
				),
				$supported_services[ $id ]
			);
			$sharing_link = '<amp-social-share';
			foreach ( $args as $key => $value ) {
				$sharing_link .= sprintf( ' %s="%s"', sanitize_key( $key ), esc_attr( $value ) );
			}
			$sharing_link   .= '></amp-social-share>';
			$sharing_links[] = $sharing_link;
		}

		// Wrap AMP sharing buttons in container.
		$markup = preg_replace( '#(?<=<div class="sd-content">).+?(?=</div>)#s', implode( '', $sharing_links ), $markup );

		// Remove any lingering share-end list items.
		$markup = str_replace( '<li class="share-end"></li>', '', $markup );

		return $markup;
	}

	/**
	 * Tells Jetpack not to enqueue CSS for share buttons.
	 *
	 * @param  bool $enqueue Whether or not to enqueue.
	 * @return bool          Whether or not to enqueue.
	 */
	public static function amp_disable_sharedaddy_css( $enqueue ) {
		if ( self::is_amp_request() ) {
			$enqueue = false;
		}

		return $enqueue;
	}

	/**
	 * Ensure proper Photon image dimensions for AMP Stories.
	 *
	 * @param array $args Array of Photon Arguments.
	 * @param array $details {
	 *     Array of image details.
	 *
	 *     @type string    $tag            Image tag (Image HTML output).
	 *     @type string    $src            Image URL.
	 *     @type string    $src_orig       Original Image URL.
	 *     @type int|false $width          Image width.
	 *     @type int|false $height         Image height.
	 *     @type int|false $width_orig     Original image width before constrained by content_width.
	 *     @type int|false $height_orig    Original Image height before constrained by content_width.
	 *     @type string    $transform_orig Original transform before constrained by content_width.
	 * }
	 * @return array Args.
	 */
	public static function filter_photon_post_image_args_for_stories( $args, $details ) {
		if ( ! is_singular( 'amp_story' ) ) {
			return $args;
		}

		// Percentage-based dimensions are not allowed in AMP, so this shouldn't happen, but short-circuit just in case.
		if ( false !== strpos( $details['width_orig'], '%' ) || false !== strpos( $details['height_orig'], '%' ) ) {
			return $args;
		}

		$max_height = 1280; // See image size with the slug \AMP_Story_Post_Type::MAX_IMAGE_SIZE_SLUG.
		$transform  = $details['transform_orig'];
		$width      = $details['width_orig'];
		$height     = $details['height_orig'];

		// If height is available, constrain to $max_height.
		if ( false !== $height ) {
			if ( $height > $max_height && false !== $height ) {
				$width  = ( $max_height * $width ) / $height;
				$height = $max_height;
			} elseif ( $height > $max_height ) {
				$height = $max_height;
			}
		}

		/*
		 * Set a height if none is found.
		 * If height is set in this manner and height is available, use `fit` instead of `resize` to prevent skewing.
		 */
		if ( false === $height ) {
			$height = $max_height;
			if ( false !== $width ) {
				$transform = 'fit';
			}
		}

		// Build array of Photon args and expose to filter before passing to Photon URL function.
		$args = array();

		if ( false !== $width && false !== $height ) {
			$args[ $transform ] = $width . ',' . $height;
		} elseif ( false !== $width ) {
			$args['w'] = $width;
		} elseif ( false !== $height ) {
			$args['h'] = $height;
		}

		return $args;
	}

	/**
	 *  Adds amp-options to the list of options to sync, if AMP is available
	 *
	 * @param array $options_whitelist Whitelist of options to sync.
	 * @return array Updated options whitelist
	 */
	public static function filter_jetpack_options_whitelist( $options_whitelist ) {
		if ( function_exists( 'is_amp_endpoint' ) ) {
			$options_whitelist[] = 'amp-options';
		}
		return $options_whitelist;
	}
}

add_action( 'init', array( 'Jetpack_AMP_Support', 'init' ), 1 );

add_action( 'admin_init', array( 'Jetpack_AMP_Support', 'admin_init' ), 1 );
