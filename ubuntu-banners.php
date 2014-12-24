<?php
/**
 * Plugin Name: Ubuntu Banners
 * Author: Mattia Migliorini
 * Author URI: http://deshack.net
 * Description: Ubuntu banner widgets for WordPress.
 * Version: 1.0.0
 * License: GPLv2+
 * Text Domain: ubuntu-banners
 *
 * @package  Ubuntu_Banners
 * @author Mattia Migliorini <deshack@ubuntu.com>
 * @license  GPLv2+
 * @since  1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) )
	die;

/**
 * The main plugin class.
 *
 * @package  Ubuntu_Banners
 * @author  Mattia Migliorini <deshack@ubuntu.com>
 * @since  1.0.0
 * @version  1.0.0
 */
class Ubuntu_Banners {
	/**
	 * Plugin Version.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * The plugin textdomain.
	 *
	 * This should match the Text Domain file header.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var string
	 */
	const TEXTDOMAIN = 'ubuntu-banners';

	/**
	 * The path to the views.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var string
	 */
	const VIEWS = plugin_dir_path( __FILE__ ) . 'views/';

	/**
	 * Path to the images directory.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var string
	 */
	const IMG = plugin_dir_path( __FILE__ ) . 'img/';
}