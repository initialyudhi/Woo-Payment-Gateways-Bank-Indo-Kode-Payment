<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Class WC_Gateway_Bank
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		woo-payment-gateways-bank-indo-kode-payment
 * @subpackage	Classes/WC_Gateway_Bank
 * @author		Yudhi Purnomo
 * @since		2.2.1
 */ 

class WC_Gateway_Bank extends WC_Payment_Gateway {

    /**
     * Constructor for the gateway.
     */
    public function __construct() {
		$this->id                 = 'woobankindo';
		$this->name 			  = 'Bank';   	
		
		$this->init();
    }	

    /**
     * Init the class
     */  
    function init(){
		$this->icon               = apply_filters( "woocommerce_{$this->id}_icon", WOOWIB_DIR_IMAGES. $this->id.'.svg' );
		$this->has_fields         = false;
		$this->method_title       = __( $this->name, 'woowib' );
		$this->method_description = __( "Allows payments using direct bank/wire transfer by {$this->name}.", 'woowib' );

        // Define user set variables
		$this->title        = $this->get_option( 'the_title', sprintf( __( 'Transfer via %s', 'woowib' ), $this->name ) ); //Transfer via %s
		$this->description  = $this->get_option( 'the_description', $this->method_description );
		$this->instructions = $this->get_option( 'the_instructions', $this->method_description );

		// Bank BCA account fields shown on the thanks page and in emails
		$this->account_details = get_option( "woocommerce_{$this->id}_accounts",
			array(
				'the_account_name'   => array( 
					'label' => __( 'Account name', 'woowib' ), 
					'value' => $this->get_option( 'the_account_name', '-' ) 
				),
				'the_account_number' => array(
					'label' => __( 'Number account', 'woowib' ),
					'value' => $this->get_option( 'the_account_number', '-' )
				)
			)
		);

		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    	add_action( "woocommerce_thankyou_{$this->id}", array( $this, 'thankyou_page' ) );

    	// Customer Emails
    	add_action( 'woocommerce_email_after_order_table', array( $this, 'email_instructions' ), 10, 3 );


		// Load the settings.
		$this->init_form_fields();
		$this->init_settings(); 
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields() {
    	$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woowib' ),
				'type'    => 'checkbox',
				'label'   => __( "Enable {$this->name} Transfer", 'woowib' ),
				'default' => 'yes'
			),
			'the_title' => array(
				'title'       => __( 'Title', 'woowib' ),
				'type'        => 'text',
				'description' => __( 'This title will be seen by the customer upon checkout process', 'woowib' ),
				'default'     => __( "Direct {$this->name} Transfer", 'woowib' ),
				'desc_tip'    => true,
			),
			'the_description' => array(
				'title'       => __( 'Description', 'woowib' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'woowib' ),
				'default'     => __( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won\'t be shipped until the funds have cleared in our account.', 'woowib' ),
				'desc_tip'    => true,
			),
			'the_instructions' => array(
				'title'       => __( 'Instructions', 'woowib' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woowib' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'the_account_name' => array(
				'title'       => __( 'Account Name', 'woowib' ),
				'type'        => 'text',
				'description' => __( 'This account name is displayed during checkout process and related emails to the customer', 'woowib' ),
				'default'     => __( '', 'woowib' ),
				'desc_tip'    => true,
			),
			'the_account_number' => array(
				'title'       => __( 'Account Number', 'woowib' ),
				'type'        => 'text',
				'description' => __( 'This account number is displayed during checkout process and related emails to the customer', 'woowib' ),
				'default'     => __( '', 'woowib' ),
				'desc_tip'    => true,
			),
		);
    }

    /**
     * Output for the order received page.
     */
    public function thankyou_page( $order_id ) {
		if ( $this->instructions ) {
        	echo wpautop( wptexturize( wp_kses_post( $this->instructions ) ) );
        }
        $this->bank_details( $order_id );
    }    

    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     * @return void
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
    	if ( ! $sent_to_admin && $this->id === $order->payment_method && 'on-hold' === $order->status ) {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
			$this->bank_details( $order->id );
		}
    }

    /**
     * Get bank details and place into a list format
     */
    private function bank_details( $order_id = '' ) {
    	if ( empty( $this->account_details ) ) {
    		return;
    	}

    	$this->account_information();
    }

    /**
     * Process the payment and return the result
     *
     * @param int $order_id
     * @return array
     */
    public function process_payment( $order_id ) {

		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the payment)
		$order->update_status( 'on-hold', __( 'Awaiting payment through', 'woowib' ) );

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> $this->get_return_url( $order )
		);
    }

	/**
	 * If There are no payment fields show the description if set.
	 * Override this in your gateway if you have some.
	 *
	 * @access public
	 * @return void
	 */
	public function payment_fields() {
		if ( $description = $this->get_description() ) {
			echo wpautop( wptexturize( $description ) );
		}

		$this->account_information();
	}

	/**
	 * Display account information
	 * 
	 * @return void
	 */
	function account_information(){
		 
    	$bank_account = apply_filters( "woocommerce_{$this->id}_accounts", $this->account_details );

		$bank_account = (object) $bank_account;

		echo '<div class="account-bank-details bank-details">' . PHP_EOL;

		foreach ( $bank_account as $field_key => $field ) {
			echo '<div class="row-bank-details">' . PHP_EOL;
		    if ( ! empty( $field['value'] ) ) {
		    	echo '<div class="cell-detail ' . esc_attr( $field_key ) . ' ">' . esc_attr( $field['label'] ) . '</div><div class="cell-detail"><span class="two-dot">:</span><span class="detail-val">' . wptexturize( $field['value'] ) . '</span></div>' . PHP_EOL;
		    }

			if (  empty( $field['value'] ) && ( current_user_can('administrator') || current_user_can('editor')) ) {
				__('Manage your bank account under : Woocommerce > Settings > Payments');

			}
			echo '</div>' . PHP_EOL;
		}

		echo '</div>';
	}
}