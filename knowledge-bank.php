<?php
/**
 * Knowledge bank
 *
 * @package       KNOWLEDGEB
 * @author        Joop Laan
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Knowledge bank
 * Plugin URI:    https://mydomain.com
 * Description:   Custom post type for knowledge bank articles
 * Version:       1.0.0
 * Author:        Joop Laan
 * Author URI:    http://www.jooplaan.com/
 * Text Domain:   knowledge-bank
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Knowledge bank. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function KNOWLEDGEB() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'KNOWLEDGEB_NAME',			'Knowledge bank' );

// Plugin version
define( 'KNOWLEDGEB_VERSION',		'1.0.0' );

// Plugin Root File
define( 'KNOWLEDGEB_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'KNOWLEDGEB_PLUGIN_BASE',	plugin_basename( KNOWLEDGEB_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'KNOWLEDGEB_PLUGIN_DIR',	plugin_dir_path( KNOWLEDGEB_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'KNOWLEDGEB_PLUGIN_URL',	plugin_dir_url( KNOWLEDGEB_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once KNOWLEDGEB_PLUGIN_DIR . 'core/class-knowledge-bank.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Joop Laan
 * @since   1.0.0
 * @return  object|Knowledge_Bank
 */
function KNOWLEDGEB() {
	return Knowledge_Bank::instance();
}

KNOWLEDGEB();
