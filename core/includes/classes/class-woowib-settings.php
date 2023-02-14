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
		add_action( 'admin_menu', [$this,'add_admin_page']); 
		add_action('admin_init',[$this,'save_setting']);
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
	 * @lastupdate	2.3.0
	 * @return	string The code
	 */

	public function add_kode(){
		global $woocommerce;

		$defaultBank = ['bank_bca','bank_bri','bank_bni','bank_mandiri']; 
		$options = get_option('woowib_setting');
		$payment_method_id = WC()->session->get('chosen_payment_method');
		$checkFirstSet = get_option('_first_set_enable_payment');

		$enable = (!$options['enable_kode_payment'] || $options['enable_kode_payment']=='')?0:1;  
		$min_kode_payment = (!$options['min_kode_payment'] || $options['min_kode_payment']=='')?1:$options['min_kode_payment'];  
		$max_kode_payment = (!$options['max_kode_payment'] || $options['max_kode_payment']=='')?200:$options['max_kode_payment']; 
		$enabled_gateways = (!$options['enabled_gateways'] || $options['enabled_gateways']=='')?$defaultBank:$options['enabled_gateways'];  
		$enabled_gateways = (!$checkFirstSet)?$defaultBank:$options['enabled_gateways'];
		$title = __( 'Payment Code', 'woocommerce' );
		
		
		if ( $enable == 1 && $woocommerce->cart->subtotal != 0){
			if(! is_cart()){
                $cost = rand($min_kode_payment, $max_kode_payment);

				if($cost != 0 && in_array($payment_method_id,$enabled_gateways))
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
	 * @since	2.2.3 , disable since 2.3.0 //woocommerce_gateway_icon
	 * @return	html img
	 */
	
	function payment_icon_class_tag( $icon, $id ){ 
		if ( in_array($id,['bank_bca','bank_bni','bank_bri','bank_mandiri']) ) {
			return '<img class="payment-woobankindo payment-bank-icon icon-'.$id.'" src="' . WOOWIB_DIR_IMAGES. $id.'.png" > '; 
		} else {
			return $icon;
		}
	
	}

	/**
	 * add menu to sub general option
	 *
	 * @access	public
	 * @since	2.2.5
	 * @return	string setting menu 
	 */
	function add_admin_page() {
		 
		add_submenu_page(
	        'options-general.php',
	        'WooWIB Setting',
	        'WooWIB Setting',
	        'manage_options',
	        'woowib-setting',
	        [$this,'setting_callback'] 
	    );

	}

	
	/**
	 * callback setting html
	 *
	 * @access	public
	 * @since	2.2.5
	 * @return	html page setting
	 */

	function setting_callback(){ 
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$default_tab = null;
  		$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

		$options = get_option('woowib_setting');
		  
		include(WOOWIB_TEMPLATE_ADMIN.'html-setting.php');
	
	 
	}

	/**
	 * callback setting save action
	 *
	 * @access	public
	 * @since	2.2.5
	 * @lastupdate	2.3.0
	 * @return	boolean setting
	 */


	public function save_setting(){
		if(!isset($_POST['nonce_setting_woowib_1'])) return;

		if(!wp_verify_nonce($_POST['nonce_setting_woowib_1'],'woowib_setting')) return;
		$data = $_POST;
		unset($data['nonce_setting_woowib_1']);

		update_option('woowib_setting','');
		update_option('woowib_setting',$data);

		$checkFirstSet = get_option('_first_set_enable_payment');
		if(!$checkFirstSet){
			update_option('_first_set_enable_payment',1);
		}
	}
}
