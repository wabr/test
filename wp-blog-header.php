<?php
/**
 * Loads the wordpress environment and template.
 *
 * @package wordpress
 */

if ( ! isset( $wp_did_header ) ) {

	$wp_did_header = true;

	// Load the wordpress library.
	require_once __DIR__ . '/wp-load.php';

	// Set up the wordpress query.
	wp();

	// Load the theme template.
	require_once ABSPATH . WPINC . '/template-loader.php';

}
