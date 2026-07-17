<?php
/**
 * Theme bootstrap.
 *
 * @package OlgaMaksimenko
 */

defined( 'ABSPATH' ) || exit;

$olga_includes = array(
	'/inc/setup.php',
	'/inc/i18n.php',
	'/inc/post-types.php',
	'/inc/meta-fields.php',
	'/inc/theme-options.php',
	'/inc/helpers.php',
	'/inc/enqueue.php',
	'/inc/rest-api.php',
	'/inc/security.php',
);

foreach ( $olga_includes as $olga_file ) {
	require_once get_template_directory() . $olga_file;
}
