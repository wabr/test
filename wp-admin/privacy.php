<?php
/**
 * Privacy administration panel.
 *
 * @package wordpress
 * @subpackage Administration
 */

/** wordpress Administration Bootstrap */
require_once __DIR__ . '/admin.php';

$title = __( 'Privacy' );

list( $display_version ) = explode( '-', get_bloginfo( 'version' ) );

require_once ABSPATH . 'wp-admin/admin-header.php';
?>
<div class="wrap about__container">

	<div class="about__header">
		<div class="about__header-title">
			<p>
				<?php _e( 'wordpress' ); ?>
				<span><?php echo $display_version; ?></span>
			</p>
		</div>

		<div class="about__header-text">
			<p>
				<?php _e( 'Building more with blocks, faster and easier.' ); ?>
			</p>
		</div>

		<nav class="about__header-navigation nav-tab-wrapper wp-clearfix" aria-label="<?php esc_attr_e( 'Secondary menu' ); ?>">
			<a href="about.php" class="nav-tab"><?php _e( 'What&#8217;s New' ); ?></a>
			<a href="credits.php" class="nav-tab"><?php _e( 'Credits' ); ?></a>
			<a href="freedoms.php" class="nav-tab"><?php _e( 'Freedoms' ); ?></a>
			<a href="privacy.php" class="nav-tab nav-tab-active" aria-current="page"><?php _e( 'Privacy' ); ?></a>
		</nav>
	</div>

	<div class="about__section">
		<div class="column">
			<h1><?php _e( 'Privacy' ); ?></h1>

			<p><?php _e( 'From time to time, your wordpress site may send data to wordpress.org &#8212; including, but not limited to &#8212; the version of wordpress you are using, and a list of installed plugins and themes.' ); ?></p>

			<p>
				<?php
				printf(
					/* translators: %s: https://wordpress.org/about/stats/ */
					__( 'This data is used to provide general enhancements to wordpress, which includes helping to protect your site by finding and automatically installing new updates. It is also used to calculate statistics, such as those shown on the <a href="%s">wordpress.org stats page</a>.' ),
					__( 'https://wordpress.org/about/stats/' )
				);
				?>
			</p>

			<p>
				<?php
				printf(
					/* translators: %s: https://wordpress.org/about/privacy/ */
					__( 'We take privacy and transparency very seriously. To learn more about what data we collect, and how we use it, please visit <a href="%s">wordpress.org/about/privacy</a>.' ),
					__( 'https://wordpress.org/about/privacy/' )
				);
				?>
			</p>
		</div>
	</div>

</div>
<?php require_once ABSPATH . 'wp-admin/admin-footer.php'; ?>
