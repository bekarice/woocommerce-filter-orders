<?php
/**
 * Plugin Name: Filter WooCommerce Orders by Coupon
 * Plugin URI: http://www.skyverge.com/product/woocommerce-filter-orders/
 * Description: Adds the ability to filter orders by coupon used.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.1.0
 * Text Domain: wc-filter-orders
 *
 * GitHub Plugin URI: bekarice/woocommerce-filter-orders/
 * GitHub Branch: master
 *
 * Copyright: (c) 2015-2017 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Filter-Orders-by-Coupon
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2015-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

defined( 'ABSPATH' ) or exit;

// fire it up!
add_action( 'plugins_loaded', 'wc_filter_orders_by_coupon' );


/**
 * Plugin Description
 *
 * Adds custom filtering to the orders screen to allow filtering by coupon used.
 */
 class WC_Filter_Orders_By_Coupon {


	const VERSION = '1.1.0';

	/** @var WC_Filter_Orders_By_Coupon single instance of this plugin */
	protected static $instance;

	/**
	 * WC_Filter_Orders constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// load translations
		add_action( 'init', array( $this, 'load_translation' ) );

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

			// adds the coupon filtering dropdown to the orders page
			add_action( 'restrict_manage_posts', array( $this, 'filter_orders_by_coupon_used' ) );

			// makes coupons filterable
			add_filter( 'posts_join',  array( $this, 'add_order_items_join' ) );
			add_filter( 'posts_where', array( $this, 'add_filterable_where' ) );
		}
	}


	/** Plugin methods ***************************************/


	/**
	 * Adds the coupon filtering dropdown to the orders list
	 *
	 * @since 1.0.0
	 */
	public function filter_orders_by_coupon_used() {
		global $typenow;

		if ( 'shop_order' === $typenow ) {

			$args = array(
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'asc',
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
			);

			$coupons = get_posts( $args );

			if ( ! empty( $coupons ) ) : ?>

				<select name="_coupons_used" id="dropdown_coupons_used">
					<option value="">
						<?php esc_html_e( 'Filter by coupon used', 'wc-filter-orders' ); ?>
					</option>
					<?php foreach ( $coupons as $coupon ) : ?>
						<option value="<?php echo esc_attr( $coupon->post_title ); ?>" <?php echo esc_attr( isset( $_GET['_coupons_used'] ) ? selected( $coupon->post_title, $_GET['_coupons_used'], false ) : '' ); ?>>
							<?php echo esc_html( $coupon->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			<?php endif;
		}
	}


	/**
	 * Modify SQL JOIN for filtering the orders by any coupons used
	 *
 	 * @since 1.0.0
	 *
	 * @param string $join JOIN part of the sql query
	 * @return string $join modified JOIN part of sql query
	 */
	public function add_order_items_join( $join ) {
		global $typenow, $wpdb;

		if ( 'shop_order' === $typenow && isset( $_GET['_coupons_used'] ) && ! empty( $_GET['_coupons_used'] ) ) {

			$join .= "LEFT JOIN {$wpdb->prefix}woocommerce_order_items woi ON {$wpdb->posts}.ID = woi.order_id";
		}

		return $join;
	}


	/**
	 * Modify SQL WHERE for filtering the orders by any coupons used
	 *
	 * @since 1.0.0
	 *
	 * @param string $where WHERE part of the sql query
	 * @return string $where modified WHERE part of sql query
	 */
	public function add_filterable_where( $where ) {
		global $typenow, $wpdb;

		if ( 'shop_order' === $typenow && isset( $_GET['_coupons_used'] ) && ! empty( $_GET['_coupons_used'] ) ) {

			// Main WHERE query part
			$where .= $wpdb->prepare( " AND woi.order_item_type='coupon' AND woi.order_item_name='%s'", wc_clean( $_GET['_coupons_used'] ) );
		}

		return $where;
	}


	/** Helper methods ***************************************/


	/**
	 * Load Translations
	 *
	 * @since 1.0.0
	 */
	public function load_translation() {
		// localization
		load_plugin_textdomain( 'wc-filter-orders', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
	}


	/**
	 * Main WC_Filter_Orders_By_Coupon Instance, ensures only one instance
	 * is/can be loaded
	 *
	 * @since 1.1.0
	 *
	 * @see wc_filter_orders_by_coupon()
	 * @return WC_Filter_Orders_By_Coupon
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
		 	self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Cloning instances is forbidden due to singleton pattern.
	 *
	 * @since 1.1.0
	 */
	public function __clone() {
		/* translators: Placeholders: %s - plugin name */
		_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( 'You cannot clone instances of %s.', 'wc-filter-orders' ), 'Filter WC Orders by Coupon' ), '1.1.0' );
	}


	/**
	 * Unserializing instances is forbidden due to singleton pattern.
	 *
	 * @since 1.1.0
	 */
	public function __wakeup() {
		/* translators: Placeholders: %s - plugin name */
		_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( 'You cannot unserialize instances of %s.', 'wc-filter-orders' ), 'Filter WC Orders by Coupon' ), '1.1.0' );
	}


}


/**
 * Returns the One True Instance of WC_Filter_Orders_By_Coupon
 *
 * @since 1.1.0
 *
 * @return \WC_Filter_Orders_By_Coupon
 */
function wc_filter_orders_by_coupon() {
	return WC_Filter_Orders_By_Coupon::instance();
}
