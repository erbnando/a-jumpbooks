<?php
/**
 * Plugin Name: WooCommerce Square
 * Version: 1.0.2
 * Plugin URI: https://www.woocommerce.com/products/square/
 * Description: Adds ability to sync inventory between WooCommerce and Square POS. In addition, you can also make purchases through the Square payment gateway.
 * Author: Automattic
 * Author URI: http://www.woocommerce.com/
 * Requires at least: 4.5.0
 * Tested up to: 4.5.3
 * Requires WooCommerce at least: 2.6.0
 * Text Domain: woocommerce-square
 * Domain Path: /languages
 *
 * @package WordPress
 * @author Automattic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'e907be8b86d7df0c8f8e0d0020b52638', '1770503' );

if ( ! class_exists( 'Woocommerce_Square' ) ) :

/**
 * Main class.
 *
 * @package Woocommerce_Square
 * @since 1.0.0
 * @version 1.0.0
 */
class Woocommerce_Square {

	private static $_instance = null;

	/**
	 * @var WC_Integration
	 */
	public $integration;

	/**
	 * @var WC_Square_Client
	 */
	public $square_client;

	/**
	 * @var WC_Square_Connect
	 */
	public $square_connect;

	/**
	 * @var WC_Square_Sync_To_Square_WordPress_Hooks
	 */
	protected $wc_to_square_wp_hooks;

	/**
	 * Get the single instance aka Singleton
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Prevent cloning
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-square' ), '1.0.2' );
	}

	/**
	 * Prevent unserializing instances
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-square' ), '1.0.2' );
	}

	/**
	 * Woocommerce_Square constructor.
	 */
	private function __construct() {

		add_action( 'woocommerce_loaded', array( $this, 'bootstrap' ) );

	}

	public function bootstrap() {

		$this->define_constants();
		$this->includes();
		$this->init();
		$this->init_hooks();

		do_action( 'wc_square_loaded' );

	}

	public function init() {
		$this->integration = new WC_Square_Integration();

		$square_client = new WC_Square_Client();

		$access_token = get_option( 'woocommerce_square_merchant_access_token' );
		$square_client->set_access_token( $access_token );
		$square_client->set_merchant_id( $this->integration->get_option( 'location' ) );
		$this->square_client = $square_client;

		$this->square_connect = new WC_Square_Connect( $square_client );

		$wc_to_square_sync = new WC_Square_Sync_To_Square( $this->square_connect );
		$square_to_wc_sync = new WC_Square_Sync_From_Square( $this->square_connect );

		$inventory_poll = new WC_Square_Inventory_Poll( $this->integration, $square_to_wc_sync );

		if ( is_admin() ) {

			$bulk_handler = new WC_Square_Bulk_Sync_Handler( $this->square_connect, $wc_to_square_sync, $square_to_wc_sync );

		}

		$this->wc_to_square_wp_hooks = new WC_Square_Sync_To_Square_WordPress_Hooks( $this->integration, $wc_to_square_sync );
	}

	/**
	 * Define constants
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function define_constants() {
		define( 'WC_SQUARE_VERSION', '1.0.2' );
		define( 'WC_SQUARE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WC_SQUARE_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		// if using staging, define this in wp-config.php
		if ( ! defined( 'WC_SQUARE_ENABLE_STAGING' ) ) {
			define( 'WC_SQUARE_ENABLE_STAGING', false );
		}

		return true;
	}

	/**
	 * Include all files needed
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function includes() {
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-install.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-deactivation.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-sync-logger.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-connect.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-sync-to-square.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-sync-from-square.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-admin-integration.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-sync-to-square-wp-hooks.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-client.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-utils.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-square-inventory-poll.php' );
		require_once( dirname( __FILE__ ) . '/includes/payment/class-wc-square-payment-logger.php' );
		require_once( dirname( __FILE__ ) . '/includes/payment/class-wc-square-payments.php' );

		if ( is_admin() ) {
			require_once( dirname( __FILE__ ) . '/includes/class-wc-square-bulk-sync-handler.php' );
		}

	}

	/**
	 * Add integration settings page
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function include_integration( $integrations ) {

		$integrations[] = $this->integration;

		return $integrations;

	}

	/**
	 * Initializes hooks
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function init_hooks() {

		register_deactivation_hook( __FILE__, array( 'WC_Square_Deactivation', 'deactivate' ) );

		if ( is_woocommerce_active() ) {

			add_filter( 'woocommerce_integrations', array( $this, 'include_integration' ) );

			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

			add_action( 'woocommerce_square_bulk_syncing_square_to_wc', array( $this->wc_to_square_wp_hooks, 'disable' ) );

			add_action( 'admin_notices', array( $this, 'is_connected_to_square' ) );

		} else {

			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );

		}

	}

	/**
	 * Loads the admin JS scripts
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function enqueue_admin_scripts() {
		$current_screen = get_current_screen();

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wc-square-admin-scripts', WC_SQUARE_PLUGIN_URL . '/assets/js/wc-square-admin-scripts' . $suffix . '.js', array( 'jquery' ), WC_SQUARE_VERSION, true );

		if ( 'woocommerce_page_wc-settings' === $current_screen->id ) {

			wp_enqueue_script( 'wc-square-admin-scripts' );

			$localized_vars = array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'ajaxSyncNonce' => wp_create_nonce( '_wc_square_sync_nonce' ),
			);

			wp_localize_script( 'wc-square-admin-scripts', 'wc_square_local', $localized_vars );
		}

		return true;
	}

	/**
	 * Loads the admin CSS styles
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function enqueue_admin_styles() {
		$current_screen = get_current_screen();

		wp_register_style( 'wc-square-admin-styles', WC_SQUARE_PLUGIN_URL . '/assets/css/wc-square-admin-styles.css', null, WC_SQUARE_VERSION );

		if ( 'woocommerce_page_wc-settings' === $current_screen->id ) {

			wp_enqueue_style( 'wc-square-admin-styles' );
		}

		return true;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'woocommerce_square_plugin_locale', get_locale(), 'woocommerce-square' );

		load_textdomain( 'woocommerce-square', trailingslashit( WP_LANG_DIR ) . 'woocommerce-square/woocommerce-square' . '-' . $locale . '.mo' );

		load_plugin_textdomain( 'woocommerce-square', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		return true;
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Square Plugin requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-square' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';

		return true;
	}

	/**
	 * Shows a notice when the site is not yet connected to square.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return string
	 */
	public function is_connected_to_square() {
		$settings       = get_option( 'woocommerce_squareconnect_settings', '' );
		$existing_token = get_option( 'woocommerce_square_merchant_access_token' );

		if ( empty( $existing_token ) ) {
			echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Square is almost ready. To get started, %sconnect your Square Account.%s', 'woocommerce-square' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=integration&section=squareconnect' ) . '">', '</a>' ) . '</p></div>';
		}

		if ( empty( $settings ) || empty( $settings['location'] ) ) {
			echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Square is almost ready. Please %sset your business location.%s', 'woocommerce-square' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=integration&section=squareconnect' ) . '">', '</a>' ) . '</p></div>';
		}

		return true;
	}
}

Woocommerce_Square::instance();

endif;
