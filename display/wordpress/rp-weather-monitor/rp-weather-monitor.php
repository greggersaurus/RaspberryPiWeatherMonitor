<?php
/*
Plugin Name: rp-weather-monitor.php
Plugin URI: https://github.com/greggersaurus/RaspberryPiWeatherMonitor
Description: a Test by ggluszek
Version: 0
Author: Greg Gluszek
Author URI: https://github.com/greggersaurus
*/

/**
 * Create weather statistics graph based on attrs given.
 *
 * @param $atts
 * @return string
 */
function rp_weather_graph_shortcode( $atts ) {

	// atts tell us which data to graph
//TODO

	// knobs/dials/interactions to know data range to query?
//TODO

	// Fill in data sets from sql query
//TODO
	$datasets = '40,13,61,70 next 33,15,40,22';

	// Perform check that wp-charts plugin is present and active
//TODO

	// Call directly into wp-chart plugin functions with retreived data
	wp_charts_kickoff();
	return wp_charts_shortcode(
		array( 'type' => 'Line',
			'datasets' => $datasets,
			'labels' => 'one,two,three,four'));
}

/**
 * Necessary plugin initialization.
 *
 * @since Unknown
 */
function rp_weather_monitor_kickoff() {
	add_shortcode( 'rp_weather_graph', 'rp_weather_graph_shortcode' );
}

add_action('init', 'rp_weather_monitor_kickoff');
