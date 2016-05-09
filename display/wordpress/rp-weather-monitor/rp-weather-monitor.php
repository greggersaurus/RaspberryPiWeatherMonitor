<?php
/*
Plugin Name: Raspberry Pi Weather Monitor
Plugin URI: https://github.com/greggersaurus/RaspberryPiWeatherMonitor
Description: Allows for weather statistics gathered by Sense HAT to be disaplyed in nice charts using WordPress Charts plug-in
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
function rp_weather_graph_shortcode( $atts ) 
{
	global $wpdb;

	// atts tell us which data to graph
//TODO

	// knobs/dials/interactions to know data range to query?
//TODO

	// Fill in data sets from sql query
//TODO
	$resultSet = $wpdb->get_results("select * from weather order by id desc limit 10", ARRAY_A);

	foreach ($resultSet as $row) 
	{
		$datasets .= $row['temperature'] . ",";
		$labels .= $row['minute'] . ",";
	}

	// Perform check that wp-charts plugin is present and active
//TODO

	// Call directly into wp-chart plugin functions with retreived data
	wp_charts_kickoff();
	return wp_charts_shortcode(
		array( 'type' => 'Line',
			'datasets' => $datasets,
			'labels' => $labels));
}

/**
 * Necessary plugin initialization.
 *
 * @since Unknown
 */
function rp_weather_monitor_kickoff() 
{
	add_shortcode( 'rp_weather_graph', 'rp_weather_graph_shortcode' );
}

add_action('init', 'rp_weather_monitor_kickoff');
