<?php
/*
Plugin Name: Relative Date
Plugin URI: https://wordpress.org/plugins/wp-relative-date/
Description: Return a relative formatted date
Version: 2.0.1
Author: David Artiss
Author URI: https://artiss.blog
Text Domain: wp-relative-date
Domain Path: /languages
*/

/**
* Relative Date
*
* Main code - include various functions
*
* @package	Relative-Date
* @since	1.2
*/

/**
* Plugin initialisation
*
* Perform various functions once plugin has loaded
*
* @since	1.2
*/

function ard_plugin_init() {

	// Check option to switch out existing date functionality

	$switch = get_option( 'relative_date_switch', '' );

	// Only switch over to relative date if switch is on and we're not in admin!

	if ( $switch == 1 && !is_admin() ) {

		add_filter( 'the_date', 'ard_the_relative_date', 999, 4 );
		add_filter( 'get_the_date', 'ard_get_the_relative_date', 999, 2 );

	} else {

		remove_filter( 'the_date', 'ard_the_relative_date' );
		remove_filter( 'get_the_date', 'ard_get_the_relative_date' );
	}
}

add_action( 'init', 'ard_plugin_init' );

/**
* Add new setting using the API
*
* Uses the Settings API to add a new setting to the general screen
* This is to capture whether the blog should use relative dates by default
*
* @since	2.0
*/

function ard_settings_init() {

	add_settings_field( 'relative_date_switch', __( 'Use Relative Dates', 'wp-relative-date' ), 'ard_setting_callback', 'general' );

	register_setting( 'general', 'relative_date_switch' );

}

add_action( 'admin_init', 'ard_settings_init' );

/**
* Callback for Settings API
*
* Return additional output to add the new settings field
*
* @since	2.0
*/

 function ard_setting_callback() {

	echo '<fieldset><legend class="screen-reader-text"><span>' . __( 'Use Relative Dates', 'wp-relative-date' ) . '</span></legend><label for="relative_date_switch"><input name="relative_date_switch" id="relative_date_switch" type="checkbox" value="1" class="code"' . checked( 1, get_option( 'relative_date_switch' ), false ) . ' /> ' . __( 'Force blog to output dates as relative', 'wp-relative-date' ) . '</label></fieldset>';

 }

/**
* Add meta to plugin details
*
* Add options to plugin meta line
*
* @since	1.2
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function ard_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'wp-relative-date.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="https://wordpress.org/support/plugin/wp-relative-date">' . __( 'Support', 'wp-relative-date' ) . '</a>' ) );
		$links = array_merge( $links, array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'wp-relative-date' ) . '</a>' ) );
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'ard_set_plugin_meta', 10, 2 );

/**
* Output or return a relative date
*
* Relative replacement for the_date. Format is ignored.
*
* @since	2.0
*
* @param	string	$format		Date format (ignored)
* @param	string	$before		Text to add before the datae
* @param	string	$after		Text to add after the date
* @param	string	$echo		Whether to echo or return the relative date
* @return	string				Either the relative date or not returned
*/

function ard_the_relative_date( $format = '', $before = '', $after = '', $echo = true ) {

    $code = $before . ard_generate_date_code() . $after;

    if ( $echo ) { echo $code; return; } else { return $code; }

}

/**
* Return a relative date
*
* Relative replacement for get_the_date. Format is ignored.
*
* @since	2.0
*
* @param	string	$format		Date format (ignored)
* @param	string	$post		Post ID
* @return	string				Relative date
*/

function ard_get_the_relative_date( $format = '', $post = '' ) {

    return ard_generate_date_code( $post );

}

/**
* Output relative date
*
* Function call to output the results of a requested relative date calculation
*
* @since	1.0
*
* @param	string	$para1		First parameter (optional)
* @param	string	$para2		Second parameter (optional)
* @param	string	$para3		Third parameter (optional)
* @param	string	$para4		Fourth parameter (optional)
*/

function relative_date( $para1 = '', $para2 = '', $para3 = '', $para4 = '' ) {

    $paras = ard_extract_parameters( $para1, $para2, $para3, $para4 );

    if ( !is_array( $paras ) ) { return $paras; }

	echo ard_generate_date_code( '', $paras[ 'from' ], $paras[ 'to' ], $paras[ 'divider' ], $paras[ 'depth' ] );

	return;
}

/**
* Return relative date
*
* Function call to return the results of a requested relative date calculation
*
* @since	1.0
*
* @param	string	$para1		First parameter (optional)
* @param	string	$para2		Second parameter (optional)
* @param	string	$para3		Third parameter (optional)
* @param	string	$para4		Fourth parameter (optional)
* @return	string				Relative date
*/

function get_relative_date( $para1 = '', $para2 = '', $para3 = '', $para4 = '' ) {

    $paras = ard_extract_parameters( $para1, $para2, $para3, $para4 );

    if ( !is_array( $paras ) ) { return $paras; }

	return ard_generate_date_code( '', $paras[ 'from' ], $paras[ 'to' ], $paras[ 'divider' ], $paras[ 'depth' ] );

}

/**
* Extract parameters
*
* Extract parameters from a passed list
*
* @since	1.0
*
* @param	string	$para1		First parameter (optional)
* @param	string	$para2		Second parameter (optional)
* @param	string	$para3		Third parameter (optional)
* @param	string	$para4		Fourth parameter (optional)
* @return	string				Array of extracted parameters
*/

function ard_extract_parameters( $para1, $para2, $para3, $para4 ) {

	// Transfer parameters into an array

	$para[ 1 ] = $para1;
	$para[ 2 ] = $para2;
	$para[ 3 ] = $para3;
	$para[ 4 ] = $para4;

	// Set initial parameter values

	$date_num = 1;
	$depth = '';
	$divider = '';
	$date = array( 1 => '', 2 => '' );

	// Read array and extract parameters

	foreach ($para as $para_value) {
		if ( $para_value != '' ) {
			if ( is_numeric( $para_value ) ) {
				if ( ( $para_value == 1 ) or ( $para_value == 2 ) ) {
					$depth = $para_value;
				} else {
					if  ( $date_num == 3 ) { return ard_report_error( __( 'More than 2 dates have been specified', 'wp-relative-date' ), 'Relative Date' ); }
					$date[ $date_num ] = $para_value;
					$date_num++;
				}
			} else {
				if ( $divider != '' ) { return ard_report_error( __( 'More than 1 divider was specified', 'wp-relative-date' ), 'Relative Date' ); }
				$divider = $para_value;
			}
		}
	}

    // Convert parameters back to an array and return this

    $paras[ 'from' ] = $date[ 1 ];
    $paras[ 'to' ] = $date[ 2 ];
    $paras[ 'divider' ] = $divider;
    $paras[ 'depth' ] = $depth;

    return $paras;

}

/**
* Generate relative date
*
* Function call to create a string containing a requested relative date calculation
*
* @since	1.0
*
* @param    string  $post       Post ID (optional)
* @param	string	$from       Date to count from
* @param	string	$to		    Date to count to
* @param	string	$divider	Seperator
* @param	string	$depth	    The depth of output
* @return	string				Relative date
*/

function ard_generate_date_code( $post = '', $from = '', $to = '', $divider = ', ', $depth = 2 ) {

    // Set any missing values

    if ( $from == '' ) { $from = get_the_time( 'U', $post ); }
    if ( $to == '' ) {
        $time_gmt_adjusted = gmmktime() + ( get_option( 'gmt_offset' ) * 3600 );
        $to = $time_gmt_adjusted;
    }

    $divider = htmlspecialchars( $divider );

	// Work out which date is greater and subtract appropriately

	if ( $from > $to ) {
		$diff = $from - $to;
	} else {
		$diff = $to - $from;
	}

	// Work out how many years, months, etc, there are between the dates

	$years	 = floor( $diff / 31449600 );
	$diff	-= $years * 31449600;           // Seconds in a year
	$months	 = floor( $diff / 2620800 );
	$diff	-= $months * 2620800;           // Seconds in a month (assumes 4.3r weeks in month)
	$weeks	 =  floor( $diff / 604800 );
	$diff	-= $weeks * 604800;             // seconds in a week
	$days	 =  floor( $diff / 86400 );
	$diff	-= $days * 86400;               // seconds in a day
	$hours	 =  floor( $diff / 3600 );
	$diff	-= $hours * 3600;               // seconds in an hour
	$minutes = floor( $diff / 60 );
	$diff	-= $minutes * 60;               // seconds in a minute
	$seconds = $diff;

	$relative_date = '';

	// Build the text strings ready for translation

	$year_singular = __( 'year', 'wp-relative-date' );
	$year_plural = __( 'years', 'wp-relative-date' );
	$month_singular = __( 'month', 'wp-relative-date' );
	$month_plural = __( 'months', 'wp-relative-date' );
	$week_singular = __( 'week', 'wp-relative-date' );
	$week_plural = __( 'weeks', 'wp-relative-date' );
	$day_singular = __( 'day', 'wp-relative-date' );
	$day_plural = __( 'days', 'wp-relative-date' );
	$hour_singular = __( 'hour', 'wp-relative-date' );
	$hour_plural = __( 'hours', 'wp-relative-date' );
	$minute_singular = __( 'minute', 'wp-relative-date' );
	$minute_plural = __( 'minutes', 'wp-relative-date' );
	$second_singular = __( 'second', 'wp-relative-date' );
	$second_plural = __( 'seconds', 'wp-relative-date' );

	// Now output the results

	if ( $years > 0 ) {

		// Years and Months

		$relative_date .= ( $relative_date?$divider:'' ) . $years . ' ' . ( $years>1?$year_plural:$year_singular );
		if ( ( $months > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $months . ' ' . ( $months>1?$month_plural:$month_singular ); }
		if ( ( $months = 0 ) && ( $weeks > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $weeks . ' ' . ( $weeks>1?$week_plural:$week_singular ); }

	} elseif ( $months > 0 ) {

		// Months and weeks

		$relative_date .= ( $relative_date?$divider:'' ) . $months . ' ' . ( $months>1?$month_plural:$month_singular );
		if ( ( $weeks > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $weeks . ' ' . ( $weeks>1?$week_plural:$week_singular ); }
		if ( ( $weeks = 0 ) && ( $days > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $days . ' ' . ( $days>1?$day_plural:$day_singular ); }

	} elseif ( $weeks > 0 ) {

		// Weeks and days

		$relative_date .= ( $relative_date?$divider:'' ) . $weeks . ' ' . ( $weeks>1?$week_plural:$week_singular );
		if ( ( $days > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $days . ' '.( $days>1?$day_plural:$day_singular );}

	} elseif ( $days > 0 ) {

		// days and hours

		$relative_date .= ( $relative_date?$divider:'' ) . $days . ' ' . ( $days>1?$day_plural:$day_singular );
		if ( ( $hours > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $hours . ' ' . ( $hours>1?$hour_plural:$hour_singular ); }

	} elseif ( $hours > 0 ) {

		// hours and minutes

		$relative_date .= ( $relative_date?$divider:'' ) . $hours . ' ' . ( $hours>1?$hour_plural:$hour_singular );
		if ( ( $minutes > 0 )  && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $minutes . ' ' . ( $minutes>1?$minute_plural:$minute_singular ); }

	} elseif ( $minutes > 0 ) {

		// minutes and seconds

		$relative_date .= ( $relative_date?$divider:'' ) . $minutes . ' ' . ( $minutes>1?$minute_plural:$minute_singular );
		if ( ( $seconds > 0 ) && ( $depth == 2 ) ) { $relative_date .= ( $relative_date?$divider:'' ) . $seconds . ' ' . ( $seconds>1?$second_plural:$second_singular ); }

	} else {

		// seconds only

		$relative_date .= ( $relative_date?$divider:'' ) . $seconds . ' ' . ( $seconds>1?$second_plural:$second_singular );
	}

	return $relative_date;
}

/**
* Report an error
*
* Function to report an error
*
* @since	1.0
*
* @param	$error			string	Error message
* @param	$plugin_name	string	The name of the plugin
* @return					string	Error output
*/

function ard_report_error( $error, $plugin_name ) {

	return '<p style="color: #f00; font-weight: bold;">' . $plugin_name . ': ' . $error . "</p>\n";

}
?>
