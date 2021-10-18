<?php
/**
 * Core Administration API
 *
 * @package wordpress
 * @subpackage Administration
 * @since 2.3.0
 */

if ( ! defined( 'WP_ADMIN' ) ) {
	/*
	 * This file is being included from a file other than wp-admin/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

/** wordpress Administration Hooks */
require_once ABSPATH . 'wp-admin/includes/admin-filters.php';

/** wordpress Bookmark Administration API */
require_once ABSPATH . 'wp-admin/includes/bookmark.php';

/** wordpress Comment Administration API */
require_once ABSPATH . 'wp-admin/includes/comment.php';

/** wordpress Administration File API */
require_once ABSPATH . 'wp-admin/includes/file.php';

/** wordpress Image Administration API */
require_once ABSPATH . 'wp-admin/includes/image.php';

/** wordpress Media Administration API */
require_once ABSPATH . 'wp-admin/includes/media.php';

/** wordpress Import Administration API */
require_once ABSPATH . 'wp-admin/includes/import.php';

/** wordpress Misc Administration API */
require_once ABSPATH . 'wp-admin/includes/misc.php';

/** wordpress Misc Administration API */
require_once ABSPATH . 'wp-admin/includes/class-wp-privacy-policy-content.php';

/** wordpress Options Administration API */
require_once ABSPATH . 'wp-admin/includes/options.php';

/** wordpress Plugin Administration API */
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/** wordpress Post Administration API */
require_once ABSPATH . 'wp-admin/includes/post.php';

/** wordpress Administration Screen API */
require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
require_once ABSPATH . 'wp-admin/includes/screen.php';

/** wordpress Taxonomy Administration API */
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

/** wordpress Template Administration API */
require_once ABSPATH . 'wp-admin/includes/template.php';

/** wordpress List Table Administration API and base class */
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table-compat.php';
require_once ABSPATH . 'wp-admin/includes/list-table.php';

/** wordpress Theme Administration API */
require_once ABSPATH . 'wp-admin/includes/theme.php';

/** wordpress Privacy Functions */
require_once ABSPATH . 'wp-admin/includes/privacy-tools.php';

/** wordpress Privacy List Table classes. */
// Previously in wp-admin/includes/user.php. Need to be loaded for backward compatibility.
require_once ABSPATH . 'wp-admin/includes/class-wp-privacy-requests-table.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-privacy-data-export-requests-list-table.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-privacy-data-removal-requests-list-table.php';

/** wordpress User Administration API */
require_once ABSPATH . 'wp-admin/includes/user.php';

/** wordpress Site Icon API */
require_once ABSPATH . 'wp-admin/includes/class-wp-site-icon.php';

/** wordpress Update Administration API */
require_once ABSPATH . 'wp-admin/includes/update.php';

/** wordpress Deprecated Administration API */
require_once ABSPATH . 'wp-admin/includes/deprecated.php';

/** wordpress Multisite support API */
if ( is_multisite() ) {
	require_once ABSPATH . 'wp-admin/includes/ms-admin-filters.php';
	require_once ABSPATH . 'wp-admin/includes/ms.php';
	require_once ABSPATH . 'wp-admin/includes/ms-deprecated.php';
}
