<?php
/**
 * Plugin Name: Elementor Designer Lite
 * Description: Elementor Designer: A designer/themer's companion. Showcase your designs in style and allow users to preview before buying/downloading - EDD integrated!
 * Plugin URI: https://designsbynore.com/
 * Author: Zulfikar Nore
 * Version: 1.0.0
 * Author URI: https://designsbynore.com/
 *
 * Text Domain: elementor-designer
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_DESIGNER_VERSION', '1.0.0' );
define( 'ELEMENTOR_DESIGNER_PREVIOUS_STABLE_VERSION', '1.0.0' );

define( 'ELEMENTOR_DESIGNER__FILE__', __FILE__ );
define( 'ELEMENTOR_DESIGNER_PLUGIN_BASE', plugin_basename( ELEMENTOR_DESIGNER__FILE__ ) );
define( 'ELEMENTOR_DESIGNER_PATH', plugin_dir_path( ELEMENTOR_DESIGNER__FILE__ ) );
define( 'ELEMENTOR_DESIGNER_MODULES_PATH', ELEMENTOR_DESIGNER_PATH . 'modules/' );
define( 'ELEMENTOR_DESIGNER_URL', plugins_url( '/', ELEMENTOR_DESIGNER__FILE__ ) );
define( 'ELEMENTOR_DESIGNER_ASSETS_URL', ELEMENTOR_DESIGNER_URL . 'assets/' );
define( 'ELEMENTOR_DESIGNER_MODULES_URL', ELEMENTOR_DESIGNER_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_designer_load_plugin() {
	load_plugin_textdomain( 'elementor-designer' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_designer_fail_load' );
		return;
	}

	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_designer_fail_load_out_of_date' );
		return;
	}

	$elementor_version_recommendation = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_designer_admin_notice_upgrade_recommendation' );
	}

	require( ELEMENTOR_DESIGNER_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'elementor_designer_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_designer_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'Elementor Designer is not working because you need to activate the Elementor plugin.', 'elementor-designer' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'elementor-designer' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Elementor Designer is not working because you need to install the Elementor plugin', 'elementor-designer' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'elementor-designer' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function elementor_designer_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Designer not working because you are using an old version of Elementor.', 'elementor-designer' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-designer' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function elementor_designer_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of Elementor Designer, we recommend updating to the latest version.', 'elementor-designer' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-designer' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

/**
 * Register and enqueue a custom stylesheet in the Elementor.
 */
add_action('elementor/editor/after_enqueue_scripts', function(){
	wp_enqueue_style( 'designer-elementor-editor', plugins_url( '/assets/css/dee.css', ELEMENTOR_DESIGNER__FILE__ ) );
});

/*
 * Convert variable prices from radio buttons to a dropdown
 */
function designer_edd_purchase_variable_pricing( $download_id ) {
	$variable_pricing = edd_has_variable_prices( $download_id );

	if ( ! $variable_pricing )
		return;

	$prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );

	$type   = edd_single_price_option_mode( $download_id ) ? 'checkbox' : 'radio';

	do_action( 'edd_before_price_options', $download_id );

	echo '<div class="edd_price_options">';
		if ( $prices ) {
			echo '<select name="edd_options[price_id][]">';
			foreach ( $prices as $key => $price ) {
				printf(
					'<option for="%3$s" name="edd_options[price_id][]" id="%3$s" class="%4$s" value="%5$s" %7$s> %6$s</option>',
					checked( 0, $key, false ),
					$type,
					esc_attr( 'edd_price_option_' . $download_id . '_' . $key ),
					esc_attr( 'edd_price_option_' . $download_id ),
					esc_attr( $key ),
					esc_html( $price['name'] . ' - ' . edd_currency_filter( edd_format_amount( $price[ 'amount' ] ) ) ),
					selected( isset( $_GET['price_option'] ), $key, false )
				);
				do_action( 'edd_after_price_option', $key, $price, $download_id );
			}
			echo '</select>';
		}
		do_action( 'edd_after_price_options_list', $download_id, $prices, $type );

	echo '</div><!--end .edd_price_options-->';
	do_action( 'edd_after_price_options', $download_id );
}
add_action( 'edd_purchase_link_top', 'designer_edd_purchase_variable_pricing', 10, 1 );
remove_action( 'edd_purchase_link_top', 'edd_purchase_variable_pricing', 10, 1 );