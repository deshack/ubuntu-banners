<?php
/**
 * Ubuntu Banners Widgets Interface.
 *
 * @package Ubuntu_Banners\Widgets
 * @author  Mattia Migliorini <deshack@ubuntu.com>
 * @since  1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) )
	die;

/**
 * Ubuntu Banners Widgets Interface.
 *
 * @package  Ubuntu_Banners\Widgets
 * @author  Mattia Migliorini <deshack@ubuntu.com>
 * @since  1.0.0
 * @version  1.0.0
 */
interface Ubuntu_Banners_Interface {
	/**
	 * Unique identifier for the widget.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var string
	 */
	protected $widget_slug;

	/**
	 * The widget title as visible to users.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var string
	 */
	protected $title;

	/**
	 * Retrieve the widget slug.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return  string
	 */
	public function get_widget_slug();
}