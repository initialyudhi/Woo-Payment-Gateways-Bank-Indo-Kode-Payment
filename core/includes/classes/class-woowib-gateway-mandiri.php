<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Class WC_Gateway_Mandiri
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		woo-payment-gateways-bank-indo-kode-payment
 * @subpackage	Classes/WC_Gateway_Mandiri
 * @author		Yudhi Purnomo
 * @since		2.2.1
 */

class WC_Gateway_Mandiri extends WC_Gateway_Bank {

    /**
     * Constructor for the gateway.
     */
    public function __construct() {
		$this->id                 = 'bank_mandiri';
		$this->name 			  = 'Mandiri';
		
		$this->init(); 	
    }	
}