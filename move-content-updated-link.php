<?php

/**
 * Plugin Name: Move Content Updated Link
 * Description: Moves the "Content Updated" link in the Gutenberg editor to the top right for better visibility.
 * Plugin URI:  https://github.com/robertdevore/move-content-updated-link/
 * Version:     1.0.1
 * Author:      Robert DeVore
 * Author URI:  https://robertdevore.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: move-content-updated-link
 * Domain Path: /languages
 * Update URI:  https://github.com/deviodigital/move-content-updated-link/
 */

defined( 'ABSPATH' ) || exit;

require 'includes/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/deviodigital/move-content-updated-link/',
	__FILE__,
	'move-content-updated-link'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch( 'main' );

// Check if Composer's autoloader is already registered globally.
if ( ! class_exists( 'RobertDevore\WPComCheck\WPComPluginHandler' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use RobertDevore\WPComCheck\WPComPluginHandler;

new WPComPluginHandler( plugin_basename( __FILE__ ), 'https://robertdevore.com/why-this-plugin-doesnt-support-wordpress-com-hosting/' );

/**
 * Load plugin text domain for translations
 * 
 * @since  1.0.1
 * @return void
 */
function mcul_load_textdomain() {
    load_plugin_textdomain( 
        'move-content-updated-link',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
}
add_action( 'plugins_loaded', 'mcul_load_textdomain' );

/**
 * Enqueue custom CSS for moving the "Content Updated" link.
 *
 * @since  1.0.0
 * @return void
 */
function mcul_enqueue_gutenberg_css() {
    // Check if on the post editing screen.
    if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
        return;
    }

    $screen = get_current_screen();

    // Ensure we are on a block editor screen.
    if ( isset( $screen->is_block_editor ) && $screen->is_block_editor ) {
        wp_add_inline_style(
            'wp-edit-post',
            '.components-editor-notices__snackbar {
                position: absolute;
                top: 60px;
                right: 280px;
                bottom: auto;
                left: auto;
                width: auto;
            }'
        );
    }
}
add_action( 'enqueue_block_editor_assets', 'mcul_enqueue_gutenberg_css' );
