<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       //wpminds.com
 * @since      1.0.0
 *
 * @package    Wp_Events
 * @subpackage Wp_Events/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
    settings_errors();
    global $wpe_active_tab;
    $wpe_active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general'; ?>
    <div class="wpe-header">
        <div class="wpe-header-wrap">
            <img width="40" height="40" src="<?php echo plugins_url() . '/' . WPE_PLUGIN_BASE . '/assets/img/logo.png'; ?>">
            <h1><?php _e( 'Simple WP Events Settings', 'simple-wp-events' ); ?></h1>
        </div>
    </div>
    <form method="post" action="options.php">
    <h2 class="nav-tab-wrapper">
        <?php
        do_action( 'wp_events_settings_tab' );
        ?>
    </h2>
    <div class="wpe-settings-content wrap">
        <?php
        do_action( 'wp_events_settings_content' );
        ?>
    </div>
        <?php submit_button();?>
    </form>
