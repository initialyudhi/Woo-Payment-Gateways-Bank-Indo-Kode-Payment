<?php
/**
 * WOOWIB
 *
 * @package       woo-payment-gateways-bank-indo-kode-payment
 * @author        Yudhi Purnomo
 * @license       gplv2
 * @version       2.2.5
 *
 * @wordpress-plugin
 * Plugin Name:   WooWIB - Payment Gateways Bank Indonesia
 * Plugin URI:    #
 * Description:   Payment gateway with indonesia Banks and code payment. Add Indonesian Banks to WooCommerce payment gateway and Code payment to make it easier to check the transfer of consumer funds in the seller's account. Code payment is 3 digits (random) added to total shopping automatically.
 * Version:       2.2.5.1
 * Text Domain: woowib
 * Requires at least: 5.7.0
 * Tested up to: 6.1.1
 * WC requires at least: 6.7.0
 * WC tested up to: 7.2.2
 * Stable tag: 2.2.5
 * Author:        Yudhi Purnomo
 * Author URI:    mailto:yudhipur19@gmail.com 
 * Domain Path:   /languages
 * License:       GPLv3
 * License URI:   http://www.gnu.org/licenses/gpl-3.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with WOOWIB. If not, see <http://www.gnu.org/licenses/gpl-3.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
   
	if ( class_exists( 'WooCommerce' ) ) {
	
		echo '<div class="notice notice-error is-dismissible"><p> WooCommerce is not Active!  (WooWIB - Payment Gateways Bank Indonesia)</p></div>';
	} else {
		echo '<div class="notice notice-error is-dismissible"><p> WooCommerce is not installed! (WooWIB - Payment Gateways Bank Indonesia)</p></div>';		 
	     
	}
	return;

}

define( 'WOOWIB_NAME',			'WooWIB - Payment Gateways Bank Indonesia' );
define( 'WOOWIB_VERSION',		'2.2.5' );
define( 'WOOWIB_NONCE',		'_woowib-225' );
define( 'WOOWIB_PLUGIN_FILE',	__FILE__ );
define( 'WOOWIB_PLUGIN_BASE',	plugin_basename( WOOWIB_PLUGIN_FILE ) );
define( 'WOOWIB_PLUGIN_DIR',	plugin_dir_path( WOOWIB_PLUGIN_FILE ) );
define( 'WOOWIB_TEMPLATE_DIR',	WOOWIB_PLUGIN_DIR.'templates/' );
define( 'WOOWIB_TEMPLATE_ADMIN',	WOOWIB_TEMPLATE_DIR.'admin/' );
define( 'WOOWIB_TEMPLATE_FRONT',	WOOWIB_TEMPLATE_DIR.'frontend/' );
define( 'WOOWIB_PLUGIN_URL',	plugin_dir_url( WOOWIB_PLUGIN_FILE ) );
define( 'WOOWIB_DIR_CSS',	WOOWIB_PLUGIN_URL.'assets/css/' );
define( 'WOOWIB_DIR_JS',	WOOWIB_PLUGIN_URL.'assets/js/' );
define( 'WOOWIB_DIR_IMAGES',	WOOWIB_PLUGIN_URL.'assets/images/' );

/**
 * Load the main class for the core functionality
 */
require_once WOOWIB_PLUGIN_DIR . 'core/class-woowib.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Yudhi Purnomo
 * @since   2.2.1
 * @return  object|WOOWIB
 */
function WOOWIB() {
	return WOOWIB::instance();
}

WOOWIB();
