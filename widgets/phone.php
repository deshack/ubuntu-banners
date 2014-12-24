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
	 * List of available languages.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $languages = array(
		'en' => __('English', Ubuntu_Banners::TEXTDOMAIN ),
		'es' => __('Spanish', Ubuntu_Banners::TEXTDOMAIN ),
		'it' => __('Italian', Ubuntu_Banners::TEXTDOMAIN )
	);

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

		$title = apply_filters( 'widget_title', $title );
		$image = $this->get_banner_image( $language );
		
		$widget_string = $before_widget;

		ob_start();

		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		echo '<a href="http://www.ubuntu.com/phone" target="_blank">';
		echo '<img src="' . esc_url( $image ) . '" id="' . esc_attr( $this->get_widget_slug() ) . '" border="0"';
		if ( ! is_null( $width ) )
			echo ' width="' . esc_attr( $width ) . '"';
		if ( ! is_null( $height ) )
			echo ' height="' . esc_attr( $height ) . '"';
		echo ' alt="' . esc_attr( $alt ) . '">';
		echo '</a>';

		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		// This way the cache expires at midnight.
		$now = time();
		$tomorrow = strtotime('tomorrow');
		$expire = $tomorrow - $now;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget', $expire );

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
		foreach ( array_keys( $instance ) as $k )
			$instance[$k] = $new_instance[$k];

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
		$defaults = array(
			'title' => '',
			'alt' => 'Ubuntu Phone Launch Event',
			'width' => '',
			'height' => '',
			'language' => 'en'
		);
		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		// Store the values of the widget in their own variable.
		extract( $instance, EXTR_SKIP );

		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e( 'Title:', Ubuntu_Banners::TEXTDOMAIN ); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id('alt'); ?>">
		<?php _e( 'Alternative text:', Ubuntu_Banners::TEXTDOMAIN ); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo $alt; ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id('width'); ?>">
		<?php _e( 'Width:', Ubuntu_Banners::TEXTDOMAIN ); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id('height'); ?>">
		<?php _e( 'Height:', Ubuntu_Banners::TEXTDOMAIN ); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id('language'); ?>">
		<?php _e( 'Language:', Ubuntu_Banners::TEXTDOMAIN ); ?>
	</label>
	<select id="<?php echo $this->get_field_id('language'); ?>" name="<?php echo $this->get_field_name('language'); ?>">
	<?php foreach ( self::$languages as $k => $v ) : ?>
		<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $language, $k ); ?>><?php echo $v; ?></option>
	<?php endforeach; ?>
	</select>
</p>
		<?php
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

	/**
	 * Retrieve the banner image based on days.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @param  string $lang The desired language.
	 * @return  string The path to the image.
	 */
	private function get_banner_image( $lang ) {
		$now = time();
		$release = strtotime("2015-02-06");
		$unix_days = $now - $release;
		$days = floor( $unix_days/ (60*60*24) );
		if ( $days > 1 ) {
			$days = 1;
		}
		return Ubuntu_Banners::IMG . $lang . '/' . $days . '.png';
	}
}

add_action( 'widgets_init', function() {
	register_widget( 'Ubuntu_Phone_Countdown' );
} );