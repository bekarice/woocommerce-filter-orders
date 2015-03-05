=== WooCommerce Filter Orders by Coupon ===
Contributors: skyverge, beka.rice
Tags: woocommerce, orders, filter orders, coupons
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal@skyverge.com&item_name=Donation+for+WooCommerce+Extra+Product+Sorting
Requires at least: 3.8
Tested up to: 4.1
Requires WooCommerce at least: 2.2
Tested WooCommerce up to: 2.3
Stable Tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds custom filtering to the orders screen to allow filtering by coupon used.

== Description ==

This plugin adds a new filtering option to the orders screen. This allows you to filter your orders by the coupon used within the order.

> **Requires: WooCommerce 2.2+**, Compatible with WooCommerce 2.3

**There are no settings**. The plugin will add the filtering dropdown automatically to the "Orders" screen while active.

= Fast Coupon Filtering =
While you can search for the coupon used, this isn't ideal to find orders that have used a coupon, as you may have product names that contain the coupon name. This plugin adds a filtering option to return exact results for orders that have used a particular coupon.

Only coupons that are "published" (no drafts) will be available in the filtering dropdown.

= More Details =
 - See the [product page](http://www.skyverge.com/product/woocommerce-filter-orders/) for full details.
 - View more of SkyVerge's [free WooCommerce extensions](http://profiles.wordpress.org/skyverge/)
 - View all [SkyVerge WooCommerce extensions](http://www.skyverge.com/shop/)
 - View the FAQ for some tips.

== Installation ==

1. Be sure you're running WooCommerce 2.2+ in your shop.
2. Upload the entire `woocommerce-filter-orders-by-coupon` folder to the `/wp-content/plugins/` directory, or upload the .zip file with the plugin under **Plugins &gt; Add New &gt; Upload**
3. Activate the plugin through the **Plugins** menu in WordPress

You can now filter your orders by coupons available in your shop by visiting **WooCommerce &gt; Orders** in your admin.

== Frequently Asked Questions ==
= Why don't all of my coupons show up in the dropdown? =
Only coupons with a status of "published" will be shown here. If your coupon is set up as draft, it won't be used for filtering.

= I don't see a dropdown at all! What gives? =
The coupon filtering dropdown will only show if coupons are present in your shop and published :).

= Can I filter for orders that have used more than one coupon? =
Sorry, this functionality isn't available. This plugin is simply meant to be an easy way to check for orders that contain a particular coupon. If an order uses more than one coupon, it will still be included when filtering, so long as the coupon you're filtering for was used.

= Can I translate this in my language? =
Yep! There's only one string to translate, but you can do so :). The coupon names are pulled directly from your coupon list, so they can be translated there.

The text domain to use is `wc-filter-orders`.

= This is handy! Can I contribute? =
Yes you can! Join in on our [GitHub repository](https://github.com/bekarice/woocommerce-filter-orders/) and submit a pull request :)

== Screenshots ==
1. The new coupon filter added to the Orders page

== Changelog ==

= 2015.03.06 - version 1.0.0 =
 * Initial Release