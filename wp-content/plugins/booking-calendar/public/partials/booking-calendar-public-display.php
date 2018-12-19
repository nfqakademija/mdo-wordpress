<?php

class Calendar_Public_Display {

    public function __construct()
    {
        //Add shortcode
        add_shortcode('booking-calendar', array($this, 'booked_calendar_shortcode') );
    }


    /* CALENDAR SHORTCODE */
    public function booked_calendar_shortcode($atts, $content = null){
		        $local_time = current_time('timestamp');
        $calendars = get_terms('booked_custom_calendars',array('orderby'=>'name','order'=>'ASC','hide_empty'=>false));

        $atts = shortcode_atts(
            array(
                'size' => 'large',
                'calendar' => false,
                'year' => false,
                'month' => false,
                'day' => false,
                'switcher' => false,
                'style' => 'calendar',
                'members-only' => false
            ), $atts );
		
		 if ($atts['members-only'] && is_user_logged_in() || !$atts['members-only']):

            ob_start();

            $atts = apply_filters('booked_calendar_shortcode_atts', $atts );
            $rand = rand(0000000,9999999);

            echo '<div class="booked-calendar-shortcode-wrap">';

            $home_url = $this->booked_home_url();

            echo '<div id="data-ajax-url">'.$home_url.'/</div>';

            if ($atts['switcher']):
                $args = array(
                    'taxonomy'		=> 'booked_custom_calendars',
                    'hide_empty'	=> 0,
                    'echo'			=> 0,
                    'id'			=> 'booked_calendar_chooser_'.$rand,
                    'name'			=> 'booked_calendar_chooser_'.$rand,
                    'class'			=> 'booked_calendar_chooser',
                    'selected'		=> $atts['calendar'],
                    'orderby'		=> 'name',
                    'order'			=> 'ASC'
                );
                if (!get_option('booked_hide_default_calendar')): $args['show_option_all'] = esc_html__('Default Calendar','booked'); endif;
                echo '<div class="booked-calendarSwitcher '.$atts['style'].'"><p><i class="booked-icon booked-icon-calendar"></i>' . str_replace( "\n", '', wp_dropdown_categories( $args ) ) . '</p></div>';
            endif;

            if (get_option('booked_hide_default_calendar') && $atts['switcher'] && !$atts['calendar'] && !empty($calendars)):
                $atts['calendar'] = $calendars[0]->term_id;
            endif;

            if ($atts['year'] || $atts['month'] || $atts['day']):
                $force_calendar = true;
                $year = ($atts['year'] ? $atts['year'] : date_i18n('Y',$local_time));
                $month = ($atts['month'] ? date_i18n('m',strtotime($year.'-'.$atts['month'].'-01')) : date_i18n('m',$local_time));
                $day = ($atts['day'] ? date_i18n('d',strtotime($year.'-'.$month.'-'.$atts['day'])) : date_i18n('d',$local_time));
                $default_date = $year.'-'.$month.'-'.$day;
            else:
                $default_date = date_i18n('Y-m-d',$local_time);
                $force_calendar = false;
            endif;

            if (!$atts['style'] || $atts['style'] != 'list'):
                echo '<div class="booked-calendar-wrap '.$atts['size'].'"'.($force_calendar ? ' data-default="'.$default_date.'"' : '').'>';
                $this->booked_fe_calendar($atts['year'],$atts['month'],$atts['calendar'],$force_calendar);
                echo '</div>';
            elseif ($atts['style'] == 'list'):
                echo '<div class="booked-list-view booked-calendar-wrap '.$atts['size'].'"'.($force_calendar ? ' data-default="'.$default_date.'"' : '').'>';
                $this->booked_fe_appointment_list_content($default_date,$atts['calendar'],$force_calendar);
                echo '</div>';
            endif;

            echo '</div>';

            wp_reset_postdata();

            return ob_get_clean();

        else:
            return false;
        endif;

    }

    // Booked Front-End Calendar
    public function booked_fe_calendar($year = false,$month = false,$calendar_id = false,$force_calendar = false){

        do_action('booked_fe_calendar_before');

        $local_time = current_time('timestamp');

        if ($calendar_id == 'undefined'): $calendar_id = 0; endif;

        $year = ($year ? $year : date_i18n('Y',$local_time));
        $month = ($month ? $month : date_i18n('m',$local_time));
        $today = date_i18n('j',$local_time);
        $currentMonth = date_i18n('Y-m-01',$local_time);

        $last_day = date_i18n('t',strtotime($year.'-'.$month));

        $monthShown = date_i18n('Y-m-01',strtotime($year.'-'.$month.'-01'));

        $first_day_of_week = 1; 	// 1 = Monday, 7 = Sunday

        // Appointments Array
        // [DAY] => [POST_ID] => [TIMESTAMP/STATUS]

        ?><table class="booked-calendar<?php echo ($booked_pa_active ? ' booked-pa-active' : ''); ?>"<?php echo ($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : ''); ?><?php echo (!$force_calendar ? ' data-calendar-date="'.$currentMonth.'"' : ''); ?>>
        <thead>

        <?php

        $next_month = date_i18n('Y-m-01', strtotime("+1 month", strtotime($year.'-'.$month.'-01')));
        $prev_month = date_i18n('Y-m-01', strtotime("-1 month", strtotime($year.'-'.$month.'-01')));

        $next_month_compare = date_i18n('Ymd',strtotime($next_month));
        if ($prevent_after && $next_month_compare > $prevent_after): $no_next_link = true; else: $no_next_link = false; endif;

        ?>

        <tr>
            <th colspan="<?php if (!$hide_weekends): ?>7<?php else: ?>5<?php endif; ?>">
                <?php if ($monthShown != $currentMonth): ?><a href="#" data-goto="<?php echo $prev_month; ?>" class="page-left"><i class="booked-icon booked-icon-arrow-left"></i></a><?php endif; ?>
                <span class="calendarSavingState">
						<i class="booked-icon booked-icon-spinner-clock booked-icon-spin"></i>
					</span>
                <span class="monthName">
						<?php echo date_i18n("F Y", strtotime($year.'-'.$month.'-01')); ?>
                    <?php if ($monthShown != $currentMonth): ?>
                        <a href="#" class="backToMonth" data-goto="<?php echo $currentMonth; ?>"><?php esc_html_e('Atgal į','booked'); ?> <?php echo date_i18n('F',strtotime($currentMonth)); ?></a>
                    <?php endif; ?>
					</span>
                <?php if (!$no_next_link): ?><a href="#" data-goto="<?php echo $next_month; ?>" class="page-right"><i class="booked-icon booked-icon-arrow-right"></i></a><?php endif; ?>
            </th>
        </tr>
        <tr class="days">
            <?php if ($first_day_of_week == 7 && !$hide_weekends): echo '<th>' . date_i18n( 'D', strtotime('Sunday') ) . '</th>'; endif; ?>
            <th><?php echo date_i18n( 'D', strtotime('Monday') ); ?></th>
            <th><?php echo date_i18n( 'D', strtotime('Tuesday') ); ?></th>
            <th><?php echo date_i18n( 'D', strtotime('Wednesday') ); ?></th>
            <th><?php echo date_i18n( 'D', strtotime('Thursday') ); ?></th>
            <th><?php echo date_i18n( 'D', strtotime('Friday') ); ?></th>
            <?php if (!$hide_weekends): ?><th><?php echo date_i18n( 'D', strtotime('Saturday') ); ?></th><?php endif; ?>
            <?php if ($first_day_of_week == 1 && !$hide_weekends): echo '<th>'. date_i18n( 'D', strtotime('Sunday') ) .'</th>'; endif; ?>
        </tr>
        </thead>
        <tbody><?php

        $today_date = date_i18n('Y',$local_time).'-'.date_i18n('m',$local_time).'-'.date_i18n('j',$local_time);
        $days = date_i18n("t",strtotime($year.'-'.$month.'-01'));	 	// Days in current month
        $lastmonth = date_i18n("t", mktime(0,0,0,$month-1,1,$year)); 	// Days in previous month

        $start = date_i18n("N", mktime(0,0,0,$month,1,$year)); 			// Starting day of current month
        if ($first_day_of_week == 7): $start = $start + 1; endif;
        if ($start > 7): $start = 1; endif;
        $finish = $days; 											// Finishing day of current month
        $laststart = $start - 1; 									// Days of previous month in calander

        $counter = 1;
        $nextMonthCounter = 1;


        //initialize calendar with data from api
        $booked_defaults = $this->init_calendar_from_api($calendar_id); //get_option('booked_defaults');

        //$booked_defaults = $this->booked_apply_custom_timeslots_filter($booked_defaults,$calendar_id);//booked_apply_custom_timeslots_filter($booked_defaults,$calendar_id);
//        echo '<pre>';
//        print_r($booked_defaults);

        $buffer = get_option('booked_appointment_buffer',0);
        $buffer_string = apply_filters('booked_appointment_buffer_string','+'.$buffer.' hours');

        if($start > 5){ $rows = 6; } else { $rows = 5; }

        for($i = 1; $i <= $rows; $i++){
            echo '<tr class="week">';
            $day_count = 0;
            for($x = 1; $x <= 7; $x++){

                $classes = array();
                $appointments_count = 0;
                $check_month = $month;

                if(($counter - $start) < 0){

                    $date = (($lastmonth - $laststart) + $counter);
                    $classes[] = 'prev-month';
                    $check_month = $month - 1;
                    if (strlen($check_month) < 2): $check_month = '0'.$check_month; endif;
                    $day_name = date('D',strtotime($year.'-'.$check_month.'-'.$date));

                } else {

                    if(($counter - $start) >= $days){

                        $date = ($nextMonthCounter);
                        $nextMonthCounter++;
                        $classes[] = 'next-month';
                        $check_month = $month + 1;
                        if (strlen($check_month) < 2): $check_month = '0'.$check_month; endif;
                        $day_name = date('D',strtotime($year.'-'.$check_month.'-'.$date));
                        if ($day_count == 0): break; endif;

                    } else {

                        $date = ($counter - $start + 1);
                        if($today == $counter - $start + 1){
                            if ($today_date == $year.'-'.$month.'-'.$date):
                                $classes[] = 'today';
                            endif;
                        }

                        $day_name = date('D',strtotime($year.'-'.$month.'-'.$date));

                    }

                }

                if ($buffer):
                    $current_timestamp = $local_time;
                    $buffered_timestamp = strtotime($buffer_string,$current_timestamp);
                    $date_to_compare = $buffered_timestamp;
                    $currentTime = date_i18n('H:i:s',$buffered_timestamp);
                else:
                    $date_to_compare = $local_time;
                    $currentTime = date_i18n('H:i:s');
                endif;

                $formatted_date = date_i18n('Ymd',strtotime($year.'-'.$check_month.'-'.$date));

                if (isset($booked_defaults[$formatted_date]) && !empty($booked_defaults[$formatted_date])):
                    $full_count = (is_array($booked_defaults[$formatted_date]) ? $booked_defaults[$formatted_date] : json_decode($booked_defaults[$formatted_date],true));
                elseif (isset($booked_defaults[$formatted_date]) && empty($booked_defaults[$formatted_date])):
                    $full_count = false;
                elseif (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name])):
                    $full_count = $booked_defaults[$day_name];
                else :
                    $full_count = false;
                endif;

                $total_full_count = 0;
                if ($full_count):
                    foreach($full_count as $full_counter){
                        $total_full_count = $total_full_count + $full_counter;
                    }
                endif;

                if (isset($booked_defaults[$formatted_date]) && !is_array($booked_defaults[$formatted_date])):
                    $booked_defaults[$formatted_date] = json_decode($booked_defaults[$formatted_date],true);
                endif;

                $appointments_count = 0;

                if (isset($appointments_array[$year.$check_month.$date]) && !empty($appointments_array[$year.$check_month.$date])):
                    foreach($appointments_array[$year.$check_month.$date] as $appt):
                        if (isset($booked_defaults[$formatted_date][$appt['timeslot']])):
                            $appointments_count++;
                        elseif (!isset($booked_defaults[$formatted_date]) && isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name]) && isset($booked_defaults[$day_name][$appt['timeslot']])):
                            $appointments_count = $appointments_count + 1;
                        endif;
                    endforeach;
                endif;

                $this_date_compare = date_i18n('Ymd',strtotime($year.'-'.$check_month.'-'.$date));

                if ($appointments_count >= $total_full_count && $total_full_count > 0):
                    if ($prevent_before && $prevent_before > $this_date_compare || $prevent_after && $this_date_compare > $prevent_after):
                        // No Booked Class added.
                    else:
                        $classes[] = 'booked';
                    endif;
                endif;

                if (
                    strtotime($year.'-'.$check_month.'-'.$date.' '.$currentTime) < $date_to_compare
                    || $prevent_before && $this_date_compare < $prevent_before
                    || $prevent_after && $this_date_compare > $prevent_after
                    || $appointments_count >= $total_full_count && strtotime($year.'-'.$check_month.'-'.$date.' '.$currentTime) < $date_to_compare
                    || $appointments_count >= $total_full_count && $total_full_count < 1
                    || $appointments_count >= $total_full_count && $prevent_before && $prevent_before > $this_date_compare
                    || $appointments_count >= $total_full_count && $prevent_after && $this_date_compare > $prevent_after):

                    $classes[] = 'prev-date';

                endif;

                $check_year = $year;

                if ($check_month == 0):
                    $check_month = 12;
                    $check_year = $year - 1;
                elseif ($check_month == 13):
                    $check_month = 1;
                    $check_year = $year + 1;
                endif;

                $check_month = date_i18n('m',strtotime($year.'-'.$check_month.'-'.$date));
                $appointments_left = 5;//booked_appointments_available($year,$check_month,$date,$calendar_id);

                if (!$appointments_left):
                    if (!$booked_pa_active):
                        if (!in_array('prev-date',$classes)):
                            $classes[] = 'prev-date';
                        endif;
                    endif;
                endif;

                $day_of_week = date_i18n('N',strtotime($check_year.'-'.$check_month.'-'.$date));

                if ($hide_weekends && $day_of_week == 6 || $hide_weekends && $day_of_week == 7):

                    $html = '';

                else:

                    $day_count++;

                    $html = '<td data-date="'.$check_year.'-'.$check_month.'-'.$date.'" class="'.implode(' ',$classes).'">';
                    $html .= '<span class="date">'. $date .'</span>';
                    $html .= '</td>';

                    $combined_date = $year.'-'.$check_month.'-'.$date;
                    echo apply_filters('booked_fe_single_date',$html,$combined_date,$classes);

                endif;

                $counter++;
                $class = '';
            }
            echo '</tr>';
        } ?>
        </tbody>
        </table><?php


    }


    public function booked_fe_appointment_list_content($date,$calendar_id = false,$force_day = false){

        $local_time = current_time('timestamp');
        $current_day = date_i18n('Ymd',$local_time);
        $public_appointments = get_option('booked_public_appointments',false);
        $total_available = 0;

        $prevent_before = apply_filters('booked_prevent_appointments_before',get_option('booked_prevent_appointments_before',false));
        $prevent_after = apply_filters('booked_prevent_appointments_after',get_option('booked_prevent_appointments_after',false));

        if ($prevent_before):
            $prevent_before = date_i18n('Ymd',strtotime($prevent_before));
        endif;

        if ($prevent_after):
            $prevent_after = date_i18n('Ymd',strtotime($prevent_after));
        endif;

        $year = date_i18n('Y',$local_time);
        $month = date_i18n('m',$local_time);
        $day = date_i18n('d',$local_time);
        $saved_date = $date;
        $counter = 0;

        do {

            $appointments_available = booked_appointments_available($year,$month,$day,$calendar_id); // get total free space for mounth

            if (!$appointments_available):
                $new_date = strtotime($year.'-'.$month.'-'.$day.' +1 day');
                $year = date_i18n('Y',$new_date);
                $month = date_i18n('m',$new_date);
                $day = date_i18n('j',$new_date);
            else:
                break;
            endif;
            $counter++;

        } while (!$appointments_available && $counter <= 365);

        if ($counter >= 365): $day = date_i18n('d',strtotime($saved_date)); $month = date_i18n('m',strtotime($saved_date)); $year = date_i18n('Y',strtotime($saved_date)); endif;
        $earliest_date = $year.'-'.$month.'-'.$day;

        if (!$force_day):

            $date = date_i18n('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
            $showing_earliest = true;

        else:

            $date = date_i18n('Y-m-d',strtotime($saved_date));
            $force_day_date = date_i18n('Ymd',strtotime($saved_date));
            if ($saved_date == $earliest_date): $showing_earliest = true; else: $showing_earliest = false; endif;

        endif;

        $prev_day = date_i18n('Ymd',strtotime($date.' -1 day'));
        $next_day = date_i18n('Ymd',strtotime($date.' +1 day'));

        $new_earliest_date = $earliest_date;

        if (isset($force_day_date) && $prev_day < $force_day_date):
            $new_earliest_date = date_i18n('Y-m-d',strtotime($force_day_date));
            $showing_earliest = true;
        endif;


        if ($prev_day >= $saved_date && $prev_day >= date_i18n('Ymd',strtotime($earliest_date))):
            $new_earliest_date = $earliest_date;
            $showing_earliest = false;
        endif;

        if ($prev_day >= date_i18n('Ymd',strtotime($earliest_date))):
            $new_earliest_date = $earliest_date;
            $showing_earliest = false;
        endif;

        $earliest_date = $new_earliest_date;

        do_action('booked_fe_calendar_date_before');

        echo '<div class="booked-appt-list" data-list-date="'.$date.'" data-min-date="'.($prevent_before ? date_i18n('Y-m-d',strtotime($prevent_before)) : $earliest_date).'" data-max-date="'.($prevent_after ? date_i18n('Y-m-d',strtotime($prevent_after)) : false).'">';

        /*
        Set some variables
        */

        $year = date_i18n('Y',strtotime($date));
        $month = date_i18n('m',strtotime($date));
        $day = date_i18n('d',strtotime($date));

        $start_timestamp = strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
        $end_timestamp = strtotime($year.'-'.$month.'-'.$day.' 23:59:59');

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $date_display = date_i18n($date_format,strtotime($date));
        $day_name = date('D',strtotime($date));

        /*
        Grab all of the appointments for this day
        */

        $args = array(
            'post_type' => 'booked_appointments',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'meta_query' => array(
                array(
                    'key'     => '_appointment_timestamp',
                    'value'   => array( $start_timestamp, $end_timestamp ),
                    'compare' => 'BETWEEN'
                )
            )
        );

        if ($calendar_id):
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'booked_custom_calendars',
                    'field'    => 'term_id',
                    'terms'    => $calendar_id,
                )
            );
        endif;

        $appointments_array = array();

        $bookedAppointments = new WP_Query( apply_filters('booked_fe_date_content_query',$args) );
        if($bookedAppointments->have_posts()):
            while ($bookedAppointments->have_posts()):
                $bookedAppointments->the_post();
                global $post;
                $timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
                $timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
                $user_id = get_post_meta($post->ID, '_appointment_user',true);
                $day = date_i18n('d',$timestamp);
                $appointments_array[$post->ID]['post_id'] = $post->ID;
                $appointments_array[$post->ID]['timestamp'] = $timestamp;
                $appointments_array[$post->ID]['timeslot'] = $timeslot;
                $appointments_array[$post->ID]['status'] = $post->post_status;
                $appointments_array[$post->ID]['user'] = $user_id;
            endwhile;
        endif;

        $appointments_array = apply_filters('booked_appointments_array', $appointments_array);

        /*
        Start the list
        */

        $this_date = date_i18n('Ymd',strtotime($date));

        if ($prevent_before && $this_date > $prevent_before || isset($current_day) && $prev_day >= $current_day && !$showing_earliest): $showing_prev = true; else: $showing_prev = false; endif;
        if ($prevent_after && $this_date >= $prevent_after): $showing_next = false; else: $showing_next = true; endif;

        ob_start();

        echo '<div class="booked-list-view-nav" data-calendar-id="'.$calendar_id.'">';
        if ($showing_prev):
            echo '<button data-date="'.date_i18n('Y-m-d',strtotime($prev_day)).'" class="booked-list-view-date-prev bb-small"><i class="booked-icon booked-icon-arrow-left"></i>&nbsp;&nbsp;'.esc_html__('Previous','booked').'</button>';
        endif;
        if ($showing_next):
            echo '<button data-date="'.date_i18n('Y-m-d',strtotime($next_day)).'" class="booked-list-view-date-next bb-small">'.esc_html__('Next','booked').'&nbsp;&nbsp;<i class="booked-icon booked-icon-arrow-right"></i></button>';
        endif;
        echo '<span class="booked-datepicker-wrap"><input data-min-date="'.$earliest_date.'" class="booked_list_date_picker" value="'.date_i18n('Y-m-d',strtotime($date)).'" type="hidden"><a href="#" class="booked_list_date_picker_trigger"><i class="booked-icon booked-icon-calendar"></i></a></span>';
        echo '</div>';

        /*
        Get today's default timeslots
        */

        if ($calendar_id):
            $booked_defaults = get_option('booked_defaults_'.$calendar_id);
            if (!$booked_defaults):
                $booked_defaults = get_option('booked_defaults');
            endif;
        else :
            $booked_defaults = get_option('booked_defaults');
        endif;

        $formatted_date = date_i18n('Ymd',strtotime($date));
        $disabled_formatted_date = date('Y-m-d',strtotime($date));
        $booked_defaults = booked_apply_custom_timeslots_details_filter($booked_defaults,$calendar_id);

        if (isset($booked_defaults[$formatted_date]) && !empty($booked_defaults[$formatted_date])):
            $todays_defaults = (is_array($booked_defaults[$formatted_date]) ? $booked_defaults[$formatted_date] : json_decode($booked_defaults[$formatted_date],true));
            $todays_defaults_details = (is_array($booked_defaults[$formatted_date.'-details']) ? $booked_defaults[$formatted_date.'-details'] : json_decode($booked_defaults[$formatted_date.'-details'],true));
        elseif (isset($booked_defaults[$formatted_date]) && empty($booked_defaults[$formatted_date])):
            $todays_defaults = false;
            $todays_defaults_details = false;
        elseif (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name])):
            $todays_defaults = $booked_defaults[$day_name];
            $todays_defaults_details = isset( $booked_defaults[$day_name.'-details'] ) ? $booked_defaults[$day_name.'-details'] : false;
        else :
            $todays_defaults = false;
            $todays_defaults_details = false;
        endif;

        /*
        There are timeslots available, let's loop through them
        */

        if ($todays_defaults){

            ksort($todays_defaults);

            $temp_count = 0;

            foreach($todays_defaults as $timeslot => $count):

                $appts_in_this_timeslot = array();

                /*
                Are there any appointments in this particular timeslot?
                If so, let's create an array of them.
                */

                foreach($appointments_array as $post_id => $appointment):
                    if ($appointment['timeslot'] == $timeslot):
                        $appts_in_this_timeslot[] = $post_id;
                    endif;
                endforeach;

                /*
                Calculate the number of spots available based on total minus the appointments booked
                */

                $spots_available = $count - count($appts_in_this_timeslot);
                $spots_available = ($spots_available < 0 ? 0 : $spots_available);

                /*
                Display the timeslot
                */

                $disabled_timeslots = get_option( 'booked_disabled_timeslots', array() );

                $timeslot_parts = explode('-',$timeslot);

                $buffer = get_option('booked_appointment_buffer',0);
                $buffer_string = apply_filters('booked_appointment_buffer_string','+'.$buffer.' hours');

                if ($buffer):
                    $current_timestamp = $local_time;
                    $buffered_timestamp = strtotime($buffer_string,$current_timestamp);
                    $current_timestamp = $buffered_timestamp;
                else:
                    $current_timestamp = $local_time;
                endif;

                $this_timeslot_timestamp = strtotime($year.'-'.$month.'-'.$day.' '.$timeslot_parts[0]);
                $spots_available = apply_filters('booked_spots_available', $spots_available, $this_timeslot_timestamp);

                if ($current_timestamp < $this_timeslot_timestamp){
                    $available = true;
                } else {
                    $available = false;
                }

                if ( $calendar_id && isset($disabled_timeslots[$calendar_id][$disabled_formatted_date][$timeslot]) || !$calendar_id && isset($disabled_timeslots[0][$disabled_formatted_date][$timeslot]) ):
                    continue;
                endif;

                $hide_unavailable_timeslots = get_option('booked_hide_unavailable_timeslots',false);
                $hide_available_count = get_option('booked_hide_available_timeslots',false);

                if ($spots_available && $available || !$hide_unavailable_timeslots):

                    $total_available = $total_available + $spots_available;

                    $temp_count++;

                    if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
                        $timeslotText = esc_html__('All day','booked');
                    else :
                        $timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])) . (!get_option('booked_hide_end_times') ? ' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])) : '');
                    endif;

                    $title = '';
                    if ( !empty( $todays_defaults_details[$timeslot] ) ) {
                        $title = !empty($todays_defaults_details[$timeslot]['title']) ? $todays_defaults_details[$timeslot]['title'] : '';
                    }

                    $only_titles = get_option('booked_show_only_titles',false);

                    if ($hide_unavailable_timeslots && !$available):
                        $html = '';
                    else:
                        $button_text = (!$spots_available || !$available ? esc_html__('Pavėlavai','booked') : esc_html__('Rezervuoti','booked'));
                        $html = '<div class="timeslot bookedClearFix'.($title && $only_titles == true ? ' booked-hide-time' : '').($hide_available_count || !$available ? ' timeslot-count-hidden' : '').(!$available ? ' timeslot-unavailable' : '').($title ? ' has-title ' : '').'">';
                        $html .= '<span class="timeslot-time'.($public_appointments ? ' booked-public-appointments' : '').'">';

                        $html .= apply_filters('booked_fe_calendar_timeslot_before','',$this_timeslot_timestamp,$timeslot,$calendar_id);

                        if ( $title ) {
                            $html .= '<span class="timeslot-title">' . esc_html($title) . '</span>';
                        }

                        $html .= '<span class="timeslot-range"><i class="booked-icon booked-icon-clock"></i>&nbsp;&nbsp;' . $timeslotText . '</span>';
                        if ($public_appointments && !empty($appts_in_this_timeslot)):
                            $html .= '<span class="booked-public-appointment-title">'._n('Appointments in this time slot:','Appointments in this time slot:',count($appts_in_this_timeslot),'booked').'</span>';
                            $html .= '<ul class="booked-public-appointment-list">';
                            foreach($appts_in_this_timeslot as $appt_id):

                                $user_id = get_post_meta($appt_id, '_appointment_user',true);
                                $html .= '<li>'.booked_get_name($user_id).'</li>';

                            endforeach;
                            $html .= '</ul>';
                        endif;

                        $html .= apply_filters('booked_fe_calendar_timeslot_after','',$this_timeslot_timestamp,$timeslot,$calendar_id);

                        $html .= '</span>';
                        $html .= '<span class="timeslot-people"><button data-calendar-id="'.$calendar_id.'" data-title="'.esc_attr($title).'" data-timeslot="'.$timeslot.'" data-date="'.$date.'" class="new-appt button"'.(!$spots_available || !$available ? ' disabled' : '').'>'.( $title ? '<span class="timeslot-mobile-title">'.esc_html($title).'</span>' : '' ).'<span class="button-timeslot">'.apply_filters('booked_fe_mobile_timeslot_button',$timeslotText,$this_timeslot_timestamp,$timeslot,$calendar_id).'</span>'.apply_filters('booked_button_book_appointment', '<span class="button-text">'.$button_text.'</span>').'</button></span>';
                        $html .= '</div>';
                    endif;

                    echo apply_filters('booked_fe_calendar_date_appointments',$html,$time_format,$timeslot_parts,$spots_available,$available,$timeslot,$date);

                endif;

            endforeach;

            if (!$temp_count):

                echo '<p>'.esc_html__('There are no appointment time slots available for this day.','booked').'</p>';

            endif;

            /*
            There are no default timeslots and no appointments booked for this particular day.
            */

        } else {
            echo '<p>'.esc_html__('There are no appointment time slots available for this day.','booked').'</p>';
        }

        $appt_list_html = ob_get_clean();

        echo $appt_list_html;

        echo '</div>';

        do_action('booked_fe_calendar_date_after');

    }

    /* Custom Time Slot Functions */
    public function booked_apply_custom_timeslots_filter($booked_defaults = false,$calendar_id = false){

        $custom_timeslots_array = array();
        $booked_custom_timeslots_encoded = get_option('booked_custom_timeslots_encoded');
        $booked_custom_timeslots_decoded = json_decode($booked_custom_timeslots_encoded,true);

        if (!empty($booked_custom_timeslots_decoded)):

            $custom_timeslots_array = booked_custom_timeslots_reconfigured($booked_custom_timeslots_decoded);
            foreach($custom_timeslots_array as $key => $value):

                if ($value['booked_custom_start_date']):

                    $formatted_date = date_i18n('Ymd',strtotime($value['booked_custom_start_date']));
                    $formatted_end_date = date_i18n('Ymd',strtotime($value['booked_custom_end_date']));

                    // To include or not to include?
                    if (!isset($value['booked_custom_calendar_id']) || $calendar_id && isset($value['booked_custom_calendar_id']) && $value['booked_custom_calendar_id'] == $calendar_id || !$calendar_id && !$value['booked_custom_calendar_id']){

                        if (!$value['booked_custom_end_date']){
                            // Single Date
                            if ($value['vacationDayCheckbox']){
                                // Time slots disabled
                                $booked_defaults[$formatted_date] = array();
                            } else {
                                // Add time slots to this date
                                $booked_defaults[$formatted_date] = $value['booked_this_custom_timelots'];
                            }
                        } else {
                            // Multiple Dates
                            $tempDate = $formatted_date;
                            do {
                                if ($value['vacationDayCheckbox']){
                                    // Time slots disabled
                                    $booked_defaults[$tempDate] = array();
                                } else {
                                    // Add time slots to this date
                                    $booked_defaults[$tempDate] = $value['booked_this_custom_timelots'];
                                }
                                $tempDate = date_i18n('Ymd',strtotime($tempDate . ' +1 day'));
                            } while ($tempDate <= $formatted_end_date);
                        }

                    }

                endif;

            endforeach;

        endif;

        return $booked_defaults;
    }

    public function booked_fe_calendar_date_content($date,$calendar_id = false){

        $total_available = 0;

        echo '<div class="booked-appt-list">';

        /*
        Set some variables
        */

        $local_time = current_time('timestamp');

        $year = date_i18n('Y',strtotime($date));
        $month = date_i18n('m',strtotime($date));
        $day = date_i18n('d',strtotime($date));

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $date_display = date_i18n($date_format,strtotime($date));

        /*
        There are timeslots available, let's loop through them
        */
        $todays_defaults = $this->get_timeslot_by_date($date, $calendar_id);
        //        echo '<pre>';
        //        print_r($todays_defaults);

        if ($todays_defaults){

            ksort($todays_defaults);

            $temp_count = 0;

            foreach($todays_defaults as $timeslot => $count):

                $appts_in_this_timeslot = array();

                /*
                Are there any appointments in this particular timeslot?
                If so, let's create an array of them.
                */

                foreach($appointments_array as $post_id => $appointment):
                    if ($appointment['timeslot'] == $timeslot):
                        $appts_in_this_timeslot[] = $post_id;
                    endif;
                endforeach;

                /*
                Calculate the number of spots available based on total minus the appointments booked
                */

                $spots_available = $count - count($appts_in_this_timeslot);
                $spots_available = ($spots_available < 0 ? 0 : $spots_available);

                /*
                Display the timeslot
                */

                $disabled_timeslots = get_option( 'booked_disabled_timeslots', array() );

                $timeslot_parts = explode('-',$timeslot);

                $buffer = get_option('booked_appointment_buffer',0);
                $buffer_string = apply_filters('booked_appointment_buffer_string','+'.$buffer.' hours');

                if ($buffer):
                    $current_timestamp = $local_time;
                    $buffered_timestamp = strtotime($buffer_string,$current_timestamp);
                    $current_timestamp = $buffered_timestamp;
                else:
                    $current_timestamp = $local_time;
                endif;

                $this_timeslot_timestamp = strtotime($year.'-'.$month.'-'.$day.' '.$timeslot_parts[0]);
                $spots_available = apply_filters('booked_spots_available', $spots_available, $this_timeslot_timestamp);

                if ($current_timestamp < $this_timeslot_timestamp){
                    $available = true;
                } else {
                    $available = false;
                }

                if ( $calendar_id && isset($disabled_timeslots[$calendar_id][$disabled_formatted_date][$timeslot]) || !$calendar_id && isset($disabled_timeslots[0][$disabled_formatted_date][$timeslot]) ):
                    continue;
                endif;

                if ($spots_available && $available || !$hide_unavailable_timeslots):

                    $total_available = $total_available + $spots_available;

                    $temp_count++;

                    if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
                        $timeslotText = esc_html__('All day','booked');
                    else :
                        $timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])) . (!get_option('booked_hide_end_times') ? ' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])) : '');
                    endif;

                    $only_titles = get_option('booked_show_only_titles',false);

                    $title = '';
                    if ( !empty( $todays_defaults_details[$timeslot] ) ) {
                        $title = !empty($todays_defaults_details[$timeslot]['title']) ? $todays_defaults_details[$timeslot]['title'] : '';
                    }

                    if ($hide_unavailable_timeslots && !$available):
                        $html = '';
                    else:
                        $button_text = (!$spots_available || !$available ? esc_html__('Pavėlavai','booked') : esc_html__('Rezervuoti','booked'));
                        $html = '<div class="timeslot bookedClearFix'.($title && $only_titles == true ? ' booked-hide-time' : '').($hide_available_count || !$available ? ' timeslot-count-hidden' : '').(!$available ? ' timeslot-unavailable' : '').($title ? ' has-title ' : '').'">';

                        $html .= '<span class="timeslot-time'.($public_appointments ? ' booked-public-appointments' : '').'">';

                        $html .= apply_filters('booked_fe_calendar_timeslot_before','',$this_timeslot_timestamp,$timeslot,$calendar_id);

                        if ( $title ) {
                            $html .= '<span class="timeslot-title">' . esc_html($title) . '</span>';
                        }

                        $html .= '<span class="timeslot-range"><i class="booked-icon booked-icon-clock"></i>&nbsp;&nbsp;' . $timeslotText . '</span>';

                        if (!$hide_available_count):
                            $html .= '<span class="spots-available'.($spots_available == 0 ? ' empty' : '').'">';
                            $html .= '</span>';
                        endif;

                        if ($public_appointments && !empty($appts_in_this_timeslot)):
                            $html .= '<span class="booked-public-appointment-title">'._n('Appointments in this time slot:','Appointments in this time slot:',count($appts_in_this_timeslot),'booked').'</span>';
                            $html .= '<ul class="booked-public-appointment-list">';
                            foreach($appts_in_this_timeslot as $appt_id):

                                $user_id = get_post_meta($appt_id, '_appointment_user',true);
                                $guest_name = get_post_meta($appt_id, '_appointment_guest_name',true);
                                $guest_surname = get_post_meta($appt_id, '_appointment_guest_surname',true);
                                $guest_email = get_post_meta($appt_id, '_appointment_guest_email',true);
                                $post_status = get_post_status($appt_id);
                                $post_status = ( $post_status == 'future' ? $post_status = 'publish' : $post_status = $post_status );

                                if ($user_id):
                                    $html .= '<li>'.booked_get_name($user_id).($post_status != 'publish' ? ' <span class="booked-public-pending">(pending)</span>' : '').'</li>';
                                elseif($guest_name):
                                    $html .= '<li>'.$guest_name.' '.$guest_surname.($post_status != 'publish' ? ' <span class="booked-public-pending">(pending)</span>' : '').'</li>';
                                endif;

                            endforeach;
                            $html .= '</ul>';
                        endif;

                        $html .= apply_filters('booked_fe_calendar_timeslot_after','',$this_timeslot_timestamp,$timeslot,$calendar_id);

                        $html .= '</span>';
                        $html .= '<span class="timeslot-people"><button data-calendar-id="'.$calendar_id.'" data-title="'.esc_attr($title).'" data-timeslot="'.$timeslot.'" data-date="'.$date.'" class="new-appt button"'.(!$spots_available || !$available ? ' disabled' : '').'>'.( $title ? '<span class="timeslot-mobile-title">'.esc_html($title).'</span>' : '' ).'<span class="button-timeslot">'.apply_filters('booked_fe_mobile_timeslot_button',$timeslotText,$this_timeslot_timestamp,$timeslot,$calendar_id).'</span>'.apply_filters('booked_button_book_appointment', '<span class="button-text">' . $button_text . '</span>' ).'</button></span>';
                        $html .= '</div>';
                    endif;

                    echo apply_filters('booked_fe_calendar_date_appointments',$html,$time_format,$timeslot_parts,$spots_available,$available,$timeslot,$date);

                endif;

            endforeach;

            if (!$temp_count):

                echo '<p>'.esc_html__('There are no appointment time slots available for this day.','booked').'</p>';

            endif;

            /*
            There are no default timeslots and no appointments booked for this particular day.
            */

        } else {
            echo '<p>'.esc_html__('There are no appointment time slots available for this day.','booked').'</p>';
        }

        $appt_list_html = ob_get_clean();

        echo $appt_list_html;

        echo '</div>';

        do_action('booked_fe_calendar_date_after');

    }

    // HELPER FUNCTION

    private function booked_home_url(){
        if (function_exists('pll_home_url')):
            $home_url = rtrim(pll_home_url(), "/");
        else:
            $home_url = rtrim(home_url(), "/");
        endif;

        return $home_url;
    }

    //API CALL METHODS

    private function init_calendar_from_api($calendar){
	if($calendar == 1){
        $response = wp_remote_get('http://mdo.projektai.nfqakademija.lt/api/sessions/1');
	}else{
		$response = wp_remote_get('http://mdo.projektai.nfqakademija.lt/api/sessions/2');
    }

    return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    private function get_timeslot_by_date($booked_defaults, $calendar_id){
        //echo $booked_defaults.'<br/>';
        //echo $calendar_id. '<br/>';

        if($calendar_id == 1){
            $response = wp_remote_get('http://mdo.projektai.nfqakademija.lt/api/sessions/1/date/'.$booked_defaults);
        }else{
            $response = wp_remote_get('http://mdo.projektai.nfqakademija.lt/api/sessions/2/date/'.$booked_defaults);
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    public function add_apointment_api($appointmentData){
        $url = 'http://mdo.projektai.nfqakademija.lt/api/sessions';
        $response = wp_remote_post( $url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => $appointmentData,
                'cookies' => array()
            )
        );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return "Something went wrong: $error_message";
        }
        //send data to symfony
        return 200;
    }
}

new Calendar_Public_Display;


