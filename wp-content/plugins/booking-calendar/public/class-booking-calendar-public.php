<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       ovioprojects.lt
 * @since      1.0.0
 *
 * @package    Booking_Calendar
 * @subpackage Booking_Calendar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Booking_Calendar
 * @subpackage Booking_Calendar/public
 * @author     Ovidijus <ovidijus104@gmail.com>
 */
class Booking_Calendar_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = '2.2';

        require_once plugin_dir_path( dirname( __FILE__ ) ). 'public/partials/booking-calendar-public-display.php';
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Booking_Calendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Booking_Calendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/booking-calendar-public.css', array(), $this->version, 'all' );
        $this->front_end_styles();
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Booking_Calendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Booking_Calendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/booking-calendar-public.js', array( 'jquery' ), $this->version, false );
        $this->front_end_scripts();
	}

    // --------- FRONT-END STYLES --------- //

    private function front_end_styles() {
        wp_enqueue_style('booked-icons', plugin_dir_url( __FILE__ ) . 'css/icons.css', array(), $this->version);
        wp_enqueue_style('booked-tooltipster', 	plugin_dir_url( __FILE__ ). 'css/tooltipster/css/tooltipster.css', array(), '3.3.0');
        wp_enqueue_style('booked-tooltipster-theme', 	plugin_dir_url( __FILE__ ) . 'css/tooltipster/css/themes/tooltipster-light.css', array(), '3.3.0');
        wp_enqueue_style('booked-animations', 	plugin_dir_url( __FILE__ ) . 'css/animations.css', array(),  $this->version);
        wp_enqueue_style('booked-styles', 		plugin_dir_url( __FILE__ ) . 'css/styles.css', array(),  $this->version);
        wp_enqueue_style('booked-responsive', 	plugin_dir_url( __FILE__ ) . 'css/responsive.css', array(),  $this->version);

    }


    // --------- FRONT-END SCRIPTS/STYLES --------- //

    private function front_end_scripts() {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_register_script('booked-atc', plugin_dir_url( __FILE__ ) . 'js/atc.min.js', array(), '1.6.1', true );
        wp_enqueue_script('booked-spin-js', 	plugin_dir_url( __FILE__ ) . 'js/spin.min.js', array(), '2.0.1', true);
        wp_enqueue_script('booked-spin-jquery', plugin_dir_url( __FILE__ ) . 'js/spin.jquery.js', array(), '2.0.1', true);
        wp_enqueue_script('booked-tooltipster', plugin_dir_url( __FILE__ ) . 'js/tooltipster/js/jquery.tooltipster.min.js', array(), '3.3.0', true);
        wp_register_script('booked-functions', plugin_dir_url( __FILE__ ) . 'js/functions.js', array(), $this->version, true);


        $booked_js_vars = array(
            'ajax_url' => $ajax_url = admin_url( 'admin-ajax.php' ), //$ajax_url,
            'profilePage' => false, //'http://google.lt', //$profile_page,
            'publicAppointments' => get_option('booked_public_appointments',false),
            'i18n_confirm_appt_delete' => esc_html__('Are you sure you want to cancel this appointment?','booked'),
            'i18n_please_wait' => esc_html__('Please wait ...','booked'),
            'i18n_wrong_username_pass' => esc_html__('Wrong username/password combination.','booked'),
            'i18n_fill_out_required_fields' => esc_html__('Please fill out all required fields.','booked'),
            'i18n_guest_appt_required_fields' => esc_html__('Please enter your name to book an appointment.','booked'),
            'i18n_appt_required_fields' => esc_html__('Please enter your name, your email address and choose a password to book an appointment.','booked'),
            'i18n_appt_required_fields_guest' => esc_html__('Užpildykite visus privalomus laukus','booked'),
            'i18n_password_reset' => esc_html__('Please check your email for instructions on resetting your password.','booked'),
            'i18n_password_reset_error' => esc_html__('That username or email is not recognized.','booked'),
        );
        wp_localize_script( 'booked-functions', 'booked_js_vars', $booked_js_vars );
        wp_enqueue_script('booked-functions');

    }

}
