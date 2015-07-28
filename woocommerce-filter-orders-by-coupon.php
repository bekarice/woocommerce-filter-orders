<?php
/**
 * Plugin Name: WooCommerce Filter Orders by Coupon
 * Plugin URI: http://www.skyverge.com/product/woocommerce-filter-orders/
 * Description: Adds the ability to filter orders by the coupon used.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.0.1
 * Text Domain: wc-filter-orders
 *
 * Copyright: (c) 2015-2015 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Filter-Orders
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2015-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Description
 *
 * Adds custom filtering to the orders screen to allow filtering by coupon used.
 *
 */
 
 class WC_Filter_Orders {
	
	
	const VERSION = '1.0.1';
	
	
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
	 * Adds the coupon filtering dropdown to the orders list
	 *
	 * @since 1.0.0
	 */
	public function filter_orders_by_coupon_used() {

		global $typenow;

		if ( 'shop_order' != $typenow ) {
	
			return;
		}
	
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'shop_coupon',
			'post_status'      => 'publish',
		);
	
		$coupons = get_posts( $args );

		$coupon_names = array();

		foreach ( $coupons as $coupon ) {

			$coupon_name = $coupon->post_title;

			array_push( $coupon_names, $coupon_name );	
 
		}
	
		if ( ! empty( $coupon_names ) ) {
		?>
		
		<select name="_coupons_used" id="dropdown_coupons_used">
			<option value=""><?php _e( 'Filter by coupon used', 'wc-filter-orders' ); ?></option>
			<?php foreach ( $coupon_names as $value => $code ) : ?>
			<option value="<?php echo $code; ?>">
				<?php printf( '%s', $code ); ?>
			</option>
			<?php endforeach; ?>
		</select>
		<?php }
	}


	/**
	 * Modify SQL JOIN for filtering the orders by any coupons used
	 *
 	 * @since 1.0.0
	 * @param string $join JOIN part of the sql query
	 * @return string $join modified JOIN part of sql query
	 */
	public function add_order_items_join( $join ) {

		global $typenow, $wpdb, $wc_coupon_names;

		if ( 'shop_order' != $typenow ) {
			return $join;
		}
	
		if ( ! empty( $_GET['_coupons_used'] ) ) {
			$join .=  "
				LEFT JOIN {$wpdb->prefix}woocommerce_order_items woi ON {$wpdb->posts}.ID = woi.order_id";
		}

		return $join;
	}



	/**
	 * Modify SQL WHERE for filtering the orders by any coupons used
	 *
	 * @since 1.0
	 * @param string $where WHERE part of the sql query
	 * @return string $where modified WHERE part of sql query
	 */
	public function add_filterable_where( $where ) {
		global $typenow, $wpdb, $wc_coupon_names;
	
		if ( 'shop_order' != $typenow ) {
			return $where;
		}
	
		if ( ! empty( $_GET['_coupons_used'] ) ) {
	
			// Main WHERE query part
			$where .= $wpdb->prepare( " AND woi.order_item_type='coupon' AND woi.order_item_name='%s'", wc_clean( $_GET['_coupons_used'] ) );
		}
	
		return $where;
	}
	
} // end \WC_Filter_Orders class

/**
 * The WC_Filter_Orders global object
 * @name $wc_filter_orders
 * @global WC_Filter_Orders $GLOBALS['wc_filter_orders']
 */
$GLOBALS['wc_filter_orders'] = new WC_Filter_Orders();