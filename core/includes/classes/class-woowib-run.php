<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class WOOWIB_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		woo-payment-gateways-bank-indo-kode-payment
 * @subpackage	Classes/WOOWIB_Run
 * @author		Yudhi Purnomo
 * @since		2.2.1
 */
class WOOWIB_Run{

	/**
	 * Our WOOWIB_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 2.2.1
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	2.2.1
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'plugin_action_links_' . WOOWIB_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	* Adds action links to the plugin list table
	*
	* @access	public
	* @since	2.2.5
	*
	* @param	array	$links An array of plugin action links.
	*
	* @return	array	An array of plugin action links.
	*
	*/
	public function add_plugin_action_link( $links ) {
		$setting_url = add_query_arg( array(  
			'page' => 'woowib-setting', 
		), admin_url( 'options-general.php' ) );

		$links['setting_page'] =  sprintf( '<a href="%s" title="Setting" style="color:#2271b1;">%s</a>', $setting_url, __( 'Setting', 'woowib' ) );
		$links['donate_us'] =  sprintf( '<a href="%s" title="Donate us" style="color:#2271b1;">%s</a>', 'https://paypal.me/initialdhi', __( 'Donate us', 'woowib' ) );

		return $links;
	}

}
