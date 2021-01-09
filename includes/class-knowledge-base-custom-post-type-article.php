<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.jooplaan.com/
 * @since      1.0.0
 *
 * @package    Knowledge_Base
 * @subpackage Knowledge_Base/includes
 */

/**
 * The class that defines the custom post type Article.
 *
 * @link       https://www.jooplaan.com/
 * @since      1.0.0
 *
 * @package    Knowledge_Base
 * @subpackage Knowledge_Base/includes
 */
class Knowledge_Base_Custom_Post_Type_Article {

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
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		// Add custom post type.
		add_action(
			'init',
			array( $this, 'register_custom_post_type' )
		);

		// Add taxonomy for article post type.
		add_action(
			'init',
			array(
				$this,
				'create_article_hierarchical_taxonomy',
			)
		);

		// Add custom fields for article post type.
		add_action(
			'category-articles_edit_form_fields',
			array( $this, 'category_articles_taxonomy_custom_fields' )
		);

		// Save action for article post type.
		add_action(
			'edited_category-articles',
			array( $this, 'save_article_custom_fields' )
		);

		add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );

	}

	/**
	 * Add article post type to search.
	 *
	 * @since 1.0.0
	 * @param object $query       The search query.
	 */
	public function filter_search( $query ) {
		if ( $query->is_search ) {
			$query->set( 'post_type', array( 'post', 'article', 'page' ) );
		};
		return $query;
	}

	/**
	 * Registers a Custom Post Type called article.
	 */
	public function register_custom_post_type() {
		$slug = __( 'knowledge-base', 'knowledge-base' );
		register_post_type(
			'article',
			array(
				'labels' => array(
					'name'               => _x( 'Articles', 'post type general name', 'knowledge-base' ),
					'singular_name'      => _x( 'Article', 'post type singular name', 'knowledge-base' ),
					'menu_name'          => _x( 'Articles', 'admin menu', 'knowledge-base' ),
					'name_admin_bar'     => _x( 'Article', 'add new on admin bar', 'knowledge-base' ),
					'add_new'            => _x( 'Add New', 'article', 'knowledge-base' ),
					'add_new_item'       => __( 'Add New article', 'knowledge-base' ),
					'new_item'           => __( 'New article', 'knowledge-base' ),
					'edit_item'          => __( 'Edit article', 'knowledge-base' ),
					'view_item'          => __( 'View article', 'knowledge-base' ),
					'all_items'          => __( 'All articles', 'knowledge-base' ),
					'search_items'       => __( 'Search articles', 'knowledge-base' ),
					'parent_item_colon'  => __( 'Parent articles:', 'knowledge-base' ),
					'not_found'          => __( 'No articles found.', 'knowledge-base' ),
					'not_found_in_trash' => __( 'No articles found in Trash.', 'knowledge-base' ),
				),
				// Frontend.
				'rewrite'            => array( 'slug' => $slug ),
				'has_archive'        => true,
				'public'             => true,
				'publicly_queryable' => true,
				// Admin.
				'capability_type'    => 'post',
				'menu_icon'          => 'dashicons-text-page',
				'menu_position'      => 6,
				'query_var'          => true,
				'show_in_menu'       => true,
				'show_ui'            => true,
				'show_in_rest'       => true,
				'supports'           => array(
					'title',
					'editor',
					'excerpt',
					'thumbnail',
					// 'custom-fields',
				),
				'taxonomies' => array( 'category-articles' ),
			)
		);
	}

	/**
	 * Registers a category for article post type.category-articles
	 */
	public function create_article_hierarchical_taxonomy() {
		$labels = array(
			'name'              => _x( 'Article categories', 'taxonomy general name', 'knowledge-base' ),
			'singular_name'     => _x( 'Article category', 'taxonomy singular name', 'knowledge-base' ),
			'search_items'      => __( 'Search article categories', 'knowledge-base' ),
			'all_items'         => __( 'All article categories', 'knowledge-base' ),
			'parent_item'       => __( 'Parent article category', 'knowledge-base' ),
			'parent_item_colon' => __( 'Parent article category:', 'knowledge-base' ),
			'edit_item'         => __( 'Edit article category', 'knowledge-base' ),
			'update_item'       => __( 'Update article category', 'knowledge-base' ),
			'add_new_item'      => __( 'Add New article category', 'knowledge-base' ),
			'new_item_name'     => __( 'New article category Name', 'knowledge-base' ),
			'menu_name'         => __( 'Article categories', 'knowledge-base' ),
		);

		// Now register the taxonomy.
		register_taxonomy(
			'category-articles',
			array( 'article' ),
			array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'public'            => true,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'articles' ),
			)
		);
	}

}
