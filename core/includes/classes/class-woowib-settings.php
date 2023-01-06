<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Class WOOWIB_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		woo-payment-gateways-bank-indo-kode-payment
 * @subpackage	Classes/WOOWIB_Settings
 * @author		Yudhi Purnomo
 * @since		2.2.1
 */

class WOOWIB_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   2.2.1
	 */
	private $plugin_name;

	/**
	 * Our WOOWIB_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 2.2.1
	 */
	function __construct(){

		$this->plugin_name = WOOWIB_NAME;

		add_action( 'plugins_loaded', 				[$this, 'load'] );
		add_filter( 'woocommerce_payment_gateways', [ $this, 'register'] );
		add_action( 'woocommerce_cart_calculate_fees', [$this, 'add_kode' ] );
		add_action( 'wp_enqueue_scripts', [$this,'loads_woowib_script_assets'] );
		add_filter( "woocommerce_gateway_icon", [$this,'payment_icon_class_tag'], 10, 2 );
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	 /**
	 * Enquiring assets files
	 * @param array
	 * 
	 * @access	public
	 * @since	2.2.1
	 * @return	void
	 */

	function loads_woowib_script_assets() {

		/** style */
		wp_enqueue_style( 'woowib-css', WOOWIB_DIR_CSS.'style.css' , false, WOOWIB_VERSION,'all' ); 
		 
		 
	}
	

 
	/**
	 * Requiring files
	 * @param array
	 * 
	 * @access	public
	 * @since	2.2.1
	 * @return	void
	 */

	function load(){

		require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-gateway-bank.php';
		require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-gateway-bca.php';
		require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-gateway-bni.php';
		require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-gateway-mandiri.php';
		require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-gateway-bri.php';
	
	}

	 /**
	 * Register payment gateways
	 * @param array
	 * 
	 * @access	public
	 * @since	2.2.1
	 * @return	array
	 */

	public function register( $methods ){
		$methods[] = 'WC_Gateway_Mandiri';
		$methods[] = 'WC_Gateway_BCA';
		$methods[] = 'WC_Gateway_BNI';
		$methods[] = 'WC_Gateway_BRI';
		
		return $methods;
	}

	/**
	 * Return kode
	 *
	 * @access	public
	 * @since	2.2.1
	 * @return	string The plugin name
	 */

	public function add_kode(){
		global $woocommerce;

		$enable = 1;  
		$title = __( 'Payment Code', 'plugins-wpkonten' );
		
		
		if ( $enable == 1 && $woocommerce->cart->subtotal != 0){
			if(! is_cart()){
                $cost = rand(100, 999);

				if($cost != 0)
					$woocommerce->cart->add_fee( __($title, 'woocommerce'), $cost);
			}
		}
    }

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	2.2.1
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'WOOWIB/settings/get_plugin_name', $this->plugin_name );
	}

	/**
	 * Return icon bank img
	 *
	 * @access	public
	 * @since	2.2.3
	 * @return	html img
	 */
	
	 function payment_icon_class_tag( $icon, $id ){ 
		if ( in_array($id,['bank_bca','bank_bni','bank_bri','bank_mandiri']) ) {
			return '<img class="payment-woobankindo payment-bank-icon icon-'.$id.'" src="' . WOOWIB_DIR_IMAGES. $id.'.png" > '; 
		} else {
			return $icon;
		}
	
	}
}
