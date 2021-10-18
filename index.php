<?php
/**
 * Front to the wordpress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells wordpress to load the theme.
 *
 * @package wordpress
 */

/**
 * Tells wordpress to load the wordpress theme and output it.
 *wsa
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the wordpress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
