<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://www.jooplaan.com/
 * @since             1.0.0
 * @package           Knowledge_Base
 *
 * @wordpress-plugin
 * Plugin Name:       Knowledge Base
 * Plugin URI:        https://github.com/jooplaan/knowledge-base
 * Description:       Post type article to create a Knowledge Base.
 * Version:           1.0.0
 * Author:            Joop Laan
 * Author URI:        https://www.jooplaan.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       knowledge-base
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'KNOWLEDGE_BASE_VERSION', '1.0.0' );

/**
 * System path.
 */
define( 'KNOWLEDGE_BASE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-knowledge-base-activator.php
 */
function activate_knowledge_base() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-knowledge-base-activator.php';
	Knowledge_Base_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-knowledge-base-deactivator.php
 */
function deactivate_knowledge_base() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-knowledge-base-deactivator.php';
	Knowledge_Base_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_knowledge_base' );
register_deactivation_hook( __FILE__, 'deactivate_knowledge_base' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-knowledge-base.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_knowledge_base() {

	$plugin = new Knowledge_Base();
	$plugin->run();

}
run_knowledge_base();
