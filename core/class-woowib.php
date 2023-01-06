<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new WOOWIB_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'WOOWIB' ) ) :

	/**
	 * Main WOOWIB Class.
	 *
	 * @package		woo-payment-gateways-bank-indo-kode-payment
	 * @subpackage	Classes/WOOWIB
	 * @since		2.2.1
	 * @author		Yudhi Purnomo
	 */
	final class WOOWIB {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	2.2.1
		 * @var		object|WOOWIB
		 */
		private static $instance;

		/**
		 * WOOWIB helpers object.
		 *
		 * @access	public
		 * @since	2.2.1
		 * @var		object|WOOWIB_Helpers
		 */
		public $helpers;

		/**
		 * WOOWIB settings object.
		 *
		 * @access	public
		 * @since	2.2.1
		 * @var		object|WOOWIB_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	2.2.1
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'woowib' ), '2.2.1' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	2.2.1
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'woowib' ), '2.2.1' );
		}

		/**
		 * Main WOOWIB Instance.
		 *
		 * Insures that only one instance of WOOWIB exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		2.2.1
		 * @static
		 * @return		object|WOOWIB	The one true WOOWIB
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WOOWIB ) ) {
				self::$instance					= new WOOWIB;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new WOOWIB_Helpers();
				self::$instance->settings		= new WOOWIB_Settings();

				//Fire the plugin logic
				new WOOWIB_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'WOOWIB/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   2.2.1
		 * @return  void
		 */
		private function includes() {
			require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-helpers.php';
			require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-settings.php';

			require_once WOOWIB_PLUGIN_DIR . 'core/includes/classes/class-woowib-run.php';
			
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   2.2.1
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   2.2.1
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'woowib', FALSE, dirname( plugin_basename( WOOWIB_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.