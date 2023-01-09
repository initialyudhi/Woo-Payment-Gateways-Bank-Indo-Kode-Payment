<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Class WC_Gateway_BRI
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		woo-payment-gateways-bank-indo-kode-payment
 * @subpackage	Classes/WC_Gateway_BRI
 * @author		Yudhi Purnomo
 * @since		2.2.1
 */

class WC_Gateway_BRI extends WC_Gateway_Bank {

    /**
     * Constructor for the gateway.
     */
    public function __construct() {
		$this->id                 = 'bank_bri';
		$this->name 			  = 'BRI';
		
		$this->init(); 	
    }	
}