<?php
$is_user_logged_in = 0; //is_user_logged_in();

$guest_booking = 1; //Confirm guest appointment //get_option( 'booked_booking_type', 'registered' ) === 'guest';
$new_appointment_default = get_option('booked_new_appointment_default','draft');

$customer_type = 'current';
if ( ! $is_user_logged_in ) {
	$customer_type = 'new';

	if ( $guest_booking ) {
		$customer_type = 'guest';
	}
}

// check the limit
$reached_limit = false;
$will_reached_limit = false;
$appointment_limit = get_option( 'booked_appointment_limit' );
if ( $is_user_logged_in && $appointment_limit ) {
	$upcoming_user_appointments = booked_user_appointments( $booked_current_user->ID, true );
	$reached_limit = $upcoming_user_appointments >= $appointment_limit;

	// check the reached limit when there are more than one appointment to book
	// in some cases the limit might be reached after booking too many appointments at a time
	if ( $total_appts > 1 ) {
		$will_reached_limit = ( $upcoming_user_appointments + $total_appts ) >= $appointment_limit;
	}
}
?>

<?php // Not logged in and guest booking is disabled ?>

<?php // The booking form ?>
<div class="condition-block customer_choice<?php echo ( $guest_booking || get_option('users_can_register') && !is_user_logged_in() || is_user_logged_in() ? ' default' : '' ); ?>" id="condition-new">
	<form action="" method="post" id="newAppointmentForm">
		<input type="hidden" name="customer_type" value="<?php echo $customer_type; ?>" />
		<input type="hidden" name="action" value="booked_add_appt" />

		<?php if ( $is_user_logged_in ): ?>
			<input type="hidden" name="user_id" value="<?php echo $booked_current_user->ID; ?>" />
		<?php endif ?>

		<?php
		$error_message = '';

		// User limit reached
		if ( $reached_limit ) {
			$error_message = sprintf(_n("Sorry, but you've hit the appointment limit. Each user may only book %d appointment at a time.","Sorry, but you've hit the appointment limit. Each user may only book %d appointments at a time.", $appointment_limit, "booked" ), $appointment_limit);
		}

		// User limit not reached yet, however, the limit will be exceeded when booking the next appointments
		if ( $will_reached_limit && ! $reached_limit ) {
			$error_message = sprintf(esc_html__("Sorry, but you're about to book more appointments than you are allowed to book at a time. Each user may only book %d appointments at a time.", "booked" ), $appointment_limit);
		}

		// Print the error message, if any
		if ( $error_message ) {
			echo wpautop( $error_message );
		}

		// If there aren't any errors, and the user is logged in
		if ( $is_user_logged_in && ! $error_message ) {
			$msg = sprintf( _n( 'You are about to request an appointment for %s.', 'You are about to request appointments for %s.', $total_appts, 'booked' ), '<em>' . booked_get_name( $booked_current_user->ID ) . '</em>' ) . ' ' . _n( 'Please review and confirm that you would like to request the following appointment:', 'Please review and confirm that you would like to request the following appointments:', $total_appts, 'booked' );
			echo wpautop( $msg );
		}

		// If there aren't any errors, and the user isn't logged in
		if ( ! $is_user_logged_in && ! $error_message ) {
			$msg = _n( 'Užpildykite ir pateikite formą, kad rezervuotumėte laiką:', 'Užpildykite ir pateikite formą, kad rezervuotumėte laiką:', $total_appts, 'booked' );
			echo wpautop( $msg );
		}

		// If no errors, list the bookings
		if ( ! $error_message ) {
			// list calendars and their appointments
			include( plugin_dir_path( __FILE__ ). 'bookings.php' );
		}

    ?>


		<div class="field">
			<p class="status"></p>
		</div>

		<div class="field">
			<?php if ( $error_message ): ?>
				<button class="cancel button"><?php esc_html_e('Okay','booked'); ?></button>
			<?php else: ?>
				<input type="submit" id="submit-request-appointment" class="button button-primary" value="<?php echo ( $new_appointment_default == 'draft' ? esc_html( _n( 'Rezervuoti', 'Rezervuoti', $total_appts, 'booked' ) ) : esc_html( _n( 'Rezervuoti', 'Rezervuoti', $total_appts, 'booked' ) ) ); ?>">
				<button class="cancel button"><?php esc_html_e('Atšaukti','booked'); ?></button>
			<?php endif; ?>
		</div>
	</form>
</div>
