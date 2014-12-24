<?php
/**
 * Ubuntu Phone countdown banner widget for WordPress.
 *
 * @package  Ubuntu_Banners\Widgets
 * @author Mattia Migliorini <deshack@ubuntu.com>
 * @since  1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) )
	die;

require_once 'interface.php';

/**
 * Ubuntu Phone Countdown widget class.
 *
 * @package Ubuntu_Banners\Widgets
 * @author  Mattia Migliorini <deshack@ubuntu.com>
 * @since  1.0.0
 * @version 1.0.0
 */
class Ubuntu_Phone_Countdown extends WP_Widget implements Ubuntu_Banners_Interface {
	/**
	 * {@inheritdoc}
	 */
	protected $widget_slug = 'ubuntu-phone-countdown';

	/**
	 * {@inheritdoc}
	 */
	protected $title = 'Ubuntu Phone Countdown';

	/**
	 * Constructor.
	 *
	 * Instantiates the widget.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct(
			$this->get_widget_slug(),
			__( $this->title, self::get_textdomain() ),
			array(
				'description' => __( 'Displays a banner for the launch of the first Ubuntu Phone.', self::get_textdomain() )
			)
		);

		// Refresh the widget's cached output with each new post.
		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * Output the content of the widget.
	 *
	 * @param  array $args The array of form elements.
	 * @param  array $instance The current instance of the widget.
	 */
	public function widget( $args, $instance ) {
		// Check if there is a cached output.
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];

		extract( $args, EXTR_SKIP );
		
		$widget_string = $before_widget;

		ob_start();

		include( Ubuntu_Banners::VIEWS . 'phone.php' );

		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;
	}

	/**
	 * Process the widget's options to be saved.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  array $new_instance The new instance values to be generated via the update.
	 * @param  array $old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Update the widget's old values with the new, incoming values.

		return $instance;
	}

	/**
	 * Generate the administration form of the widget.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		// Check incoming values against the defaults.
		$instance = wp_parse_args(
			(array) $instance
		);

		// Store the values of the widget in their own variable.
		
		// Display the admin form.
		include( Ubuntu_Banners::VIEWS . 'phone-admin.php' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}

	/**
	 * Retrieve the plugin textdomain.
	 *
	 * @since  1.0.0
	 * @access private
	 * @static
	 *
	 * @return  string
	 */
	private static function get_textdomain() {
		return Ubuntu_Banners::TEXTDOMAIN;
	}

	/**
	 * Refresh widget's cached output.
	 *
	 * Must be public in order to be passed to actions and filters.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}
}

add_action( 'widgets_init', function() {
	register_widget( 'Ubuntu_Phone_Countdown' );
} );