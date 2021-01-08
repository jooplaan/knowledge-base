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
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Knowledge_Bank_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Knowledge_Bank' ) ) :

	/**
	 * Main Knowledge_Bank Class.
	 *
	 * @package		KNOWLEDGEB
	 * @subpackage	Classes/Knowledge_Bank
	 * @since		1.0.0
	 * @author		Joop Laan
	 */
	final class Knowledge_Bank {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Knowledge_Bank
		 */
		private static $instance;

		/**
		 * KNOWLEDGEB helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Knowledge_Bank_Helpers
		 */
		public $helpers;

		/**
		 * KNOWLEDGEB settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Knowledge_Bank_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'knowledge-bank' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'knowledge-bank' ), '1.0.0' );
		}

		/**
		 * Main Knowledge_Bank Instance.
		 *
		 * Insures that only one instance of Knowledge_Bank exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Knowledge_Bank	The one true Knowledge_Bank
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Knowledge_Bank ) ) {
				self::$instance					= new Knowledge_Bank;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Knowledge_Bank_Helpers();
				self::$instance->settings		= new Knowledge_Bank_Settings();

				//Fire the plugin logic
				new Knowledge_Bank_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'KNOWLEDGEB/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once KNOWLEDGEB_PLUGIN_DIR . 'core/includes/classes/class-knowledge-bank-helpers.php';
			require_once KNOWLEDGEB_PLUGIN_DIR . 'core/includes/classes/class-knowledge-bank-settings.php';

			require_once KNOWLEDGEB_PLUGIN_DIR . 'core/includes/classes/class-knowledge-bank-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'knowledge-bank', FALSE, dirname( plugin_basename( KNOWLEDGEB_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.