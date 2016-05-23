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
//TODO graph last N {years, months, days, hours, minutes}

	// knobs/dials/interactions to know data range to query?
//TODO

	// The way this works is we are told the unit (i.e. resolution) of the
	//  graph and the number of units to graph. So the graph will show the
	//  last N {minutes, hours, days, months, years} of the requested 
	//  statistic. Each entry in the graph is an average of all the values
	//  recorded since the last unit change (i.e. request the last N hours
	//  and each entry is an average of 60 values, as we capture new stats
	//  every minute).

	// Statistic being drawn to graph
	$stat = 'temperature';
//TODO: check stat is acceptable value found in sql table
	// Each entry to graph is of a particular unit size
	$unit = 'hour';
//TODO: check unit is acceptable value found in sql table
	// Number of entries in graph
	$num_units = 48;

	// Fill in data sets from sql query
//TODO: Change query query limit based on unit and num_units desired
//TODO: always request more than needed, or change logic in for loop to add partial last entry
	$sql_results_limit = 60 * 60;

	$resultSet = $wpdb->get_results("select " . $stat . ", " . $unit . 
		" from weather order by id desc limit " . $sql_results_limit, 
		ARRAY_A);

	// Value of given unit read from last row. Used to know when we are
	//  on to the next unit value and should add an entry and restart
	//  average calulation.
	$unit_val = -1;
	// Used to count how many values were summed since last unit change
	$sum_cnt = 0;
	// Summation of statistics values used to calculate average value to be
	//  graphed
	$stat_value_sum = 0;
	// Number of entries in graph so we know when to stop
	$num_graph_entries = 0;

	// Cycle through each row and either add new value to graph, or add
	//  value to summation used to graph average value for each graph input
	foreach ($resultSet as $row) 
	{
		// When unit value changes, calculate averge of values read and
		//  add entry to graph
		if ($unit_val != $row[$unit] && $sum_cnt > 0)
		{
			// Calculate average value of statistic being graphed
			$stat_value_avg = $stat_value_sum / $sum_cnt;

			// Add entry to graph dataset
			$datasets = $stat_value_avg . "," . $datasets;
			$labels = $unit_val . "," . $labels;

			// Reset average calculation variables
			$stat_value_sum = 0;
			$sum_cnt = 0;

			// Check if we've added enough to the graph
			$num_graph_entries++;

			// Exit if this is the last entry to the graph
			if ($num_graph_entries >= $num_units)
			{
				break;
			}
		}

		// Update value of unit being averaged
		$unit_val = $row[$unit];

		// Grab the statistic value and add to sum
		$stat_value_sum += $row[$stat];

		// Increment that 
		$sum_cnt++;
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
