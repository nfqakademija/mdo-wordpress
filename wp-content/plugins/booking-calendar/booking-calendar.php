<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              ovioprojects.lt
 * @since             1.0.0
 * @package           Booking_Calendar
 *
 * @wordpress-plugin
 * Plugin Name:       booking_calendar
 * Plugin URI:        ovioprojects.lt
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ovidijus
 * Author URI:        ovioprojects.lt
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       booking-calendar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-booking-calendar-activator.php
 */
function activate_booking_calendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-booking-calendar-activator.php';
    Booking_Calendar_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-booking-calendar-deactivator.php
 */
function deactivate_booking_calendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-booking-calendar-deactivator.php';
	Booking_Calendar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_booking_calendar' );
register_deactivation_hook( __FILE__, 'deactivate_booking_calendar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-booking-calendar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_booking_calendar() {

	$plugin = new Booking_Calendar();
	$plugin->run();

    require_once plugin_dir_path( __FILE__ ). 'includes/ajax/init.php';
    $booked_ajax = new Booked_AJAX();
}
run_booking_calendar();
