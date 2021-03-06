<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.jooplaan.com/
 * @since      1.0.0
 *
 * @package    Knowledge_Base
 * @subpackage Knowledge_Base/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Knowledge_Base
 * @subpackage Knowledge_Base/public
 * @author     Joop Laan <joop@interconnecting.systems>
 */
class Knowledge_Base_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Knowledge_Base_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Knowledge_Base_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/knowledge-base-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Knowledge_Base_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Knowledge_Base_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/knowledge-base-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Custom titles for the Archive pages.
	 *
	 * @since    1.0.0
	 *
	 * @param string $title       The title.
	 */
	public function knowledge_base_archive_title( $title ) {
		$current_category = get_queried_object();
		if ( is_post_type_archive( 'article' ) ) {

			if ( 'article' == $current_category->name ) {
				$title = $this->set_title_archive( $title );
			}
		} elseif ( is_tax( 'category-articles' ) ) {

			$title = $this->set_title_archive( $title, 'h1-tag' );
		}

		return $title;
	}

	/**
	 * Custom titles for the Archive pages' <title> tag.
	 *
	 * @since    1.0.0
	 *
	 * @param string $title       The title.
	 */
	public function knowledge_base_archive_title_tag( $title ) {
		$current_category = get_queried_object();
		if ( is_post_type_archive( 'article' ) ) {

			if ( 'article' == $current_category->name ) {
				$title = $this->set_title_archive( $title, 'title-tag' );
			}
		} elseif ( is_tax( 'category-articles' ) ) {

			$title = $this->set_title_archive( $title, 'title-tag' );
		}

		return $title;
	}

	/**
	 * Custom titles for the Archive pages' <title> tag.
	 *
	 * @since    1.0.0
	 *
	 * @param string $title       The title.
	 * @param string $context     The context.
	 */
	private function set_title_archive( $title, $context = '' ) {
		$current_category = get_queried_object();
		if ( is_post_type_archive( 'article' ) ) {

			if ( 'article' == $current_category->name ) {
				$title = __( 'Knowledge Base', 'knowledge-base' );
				if ( 'title-tag' == $context ) {
					$title = $title . ' · ' . get_bloginfo( 'name' );
				}
			}
		} elseif ( is_tax( 'category-articles' ) ) {

			$title = __( 'Knowledge Base', 'knowledge-base' ) . ': ' . $current_category->name;
			if ( 'title-tag' == $context ) {
				$title = $title . ' · ' . get_bloginfo( 'name' );
			}
		}

		return $title;
	}

}
