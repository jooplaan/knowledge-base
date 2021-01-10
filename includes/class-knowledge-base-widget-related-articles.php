<?php
/**
 * Knowledge_Base_Widget_Related_articles class
 *
 * @link       https://www.jooplaan.com/
 * @since      1.0.0
 *
 * @package    Knowledge_Base
 * @subpackage Knowledge_Base/includes
 */

/**
 * Register widget.
 *
 * @since 1.3
 *
 * @see https://developer.wordpress.org/reference/hooks/widgets_init/
 */
function knowledge_base_register_widget_related_articles() {
	register_widget( 'Knowledge_Base_Widget_Related_Articles' );
}
add_action( 'widgets_init', 'knowledge_base_register_widget_related_articles' );


/**
 * Class used to implement a widget to list related articles.
 *
 * @since 1.3
 *
 * @see WP_Widget
 */
class Knowledge_Base_Widget_Related_Articles extends WP_Widget {

	/**
	 * Sets up a new Related Posts widget instance.
	 *
	 * @since 1.3
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'widget_posts',
			'description'                 => __( 'Related articles by category.', 'knowledge-base' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'related-articles', __( 'Related Articles', 'knowledge-base' ), $widget_ops );
		$this->alt_option_name = 'widget_posts';
	}

	/**
	 * Outputs the content for the current Related Posts widget instance.
	 *
	 * @since 1.3
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Related Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$default_title = __( 'Related articles', 'knowledge-base' );
		$title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/*
		 * Get the post ID.
		 */
		$post_id = get_the_ID();

		/*
		 * Get the category terms for the post.
		 */
		$categories = get_the_terms( $post_id, 'category-articles' );
		$cat_arr = array();
		$post_has_categories = false;

		if ( $categories ) {
			foreach ( $categories as $term ) {
				$cat_arr[] = $term->term_id;
			}
			if ( count( $cat_arr ) > 0 ) {
				$post_has_categories = true;
			}
		}

		if ( $post_has_categories ) {
			$c = new WP_Query(
				/**
				 * Filters the arguments for the Related Posts widget.
				 *
				 * @since 1.3
				 *
				 * @see WP_Query::get_posts()
				 *
				 * @param array $args     An array of arguments used to retrieve the related posts.
				 * @param array $instance Array of settings for the current widget.
				 */
				apply_filters(
					'widget_posts_args',
					array(
						'post_type'           => 'article',
						'posts_per_page'      => $number,
						'no_found_rows'       => true,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'post__not_in'        => array( $post_id ),
						'tax_query' => array(
							array(
								'taxonomy' => 'category-articles',
								'terms' => $cat_arr,
							),
						),
					),
					$instance
				)
			);

			if ( $c->have_posts() ) {
				$show_posts_with_same_categories = true;
			} else {
				$show_posts_with_same_categories = false;
			}
		} else {
			$show_posts_with_same_categories = false;
		}

		if ( ! $show_posts_with_same_categories ) {
			return;
		}

		?>

		<?php echo wp_kses( $args['before_widget'], $this->allowed_html() ); ?>

		<?php
		if ( $title ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], $this->allowed_html() );
		}

		$format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

		/** This filter is documented in wp-includes/widgets/class-wp-nav-menu-widget.php */
		$format = apply_filters( 'navigation_widgets_format', $format );

		if ( 'html5' === $format ) {
			// The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
			$title      = trim( strip_tags( $title ) );
			$aria_label = $title ? $title : $default_title;
			echo '<nav role="navigation" aria-label="' . esc_attr( $aria_label ) . '">';
		}
		?>

		<ul>
			<?php foreach ( $c->posts as $related_article ) : ?>
				<?php
				$post_title   = get_the_title( $related_article->ID );
				$title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
				$aria_current = '';

				if ( get_queried_object_id() === $related_article->ID ) {
					$aria_current = ' aria-current="page"';
				}
				?>
				<li>
					<a href="<?php the_permalink( $related_article->ID ); ?>"<?php echo wp_kses( $aria_current, $this->allowed_html() ); ?>><?php echo wp_kses( $title, $this->allowed_html() ); ?></a>
					<?php if ( $show_date ) : ?>
						<span class="post-date">
							<?php echo wp_kses( get_the_date( '', $related_article->ID ), $this->allowed_html() ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php
		if ( 'html5' === $format ) {
			echo '</nav>';
		}
		echo wp_kses( $args['after_widget'], $this->allowed_html() );
	}

	/**
	 * Handles updating the settings for the current Related Posts widget instance.
	 *
	 * @since 1.3
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['number']    = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Related Posts widget.
	 *
	 * @since 1.3
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$show_posts_with_same_categories    = isset( $instance['show_posts_with_same_categories'] ) ? absint( $instance['show_posts_with_same_categories'] ) : true;
		$show_posts_with_same_tags    = isset( $instance['show_posts_with_same_tags'] ) ? absint( $instance['show_posts_with_same_tags'] ) : true;
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_html( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_html( $number ); ?>" size="3" />
		</p>

		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_date' ) ); ?>" />
			<label for="<?php echo esc_html( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date', 'knowledge-base' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Allowed HTML for output.
	 *
	 * @since 1.3
	 *
	 * @return array Allowed HTML.
	 */
	private function allowed_html() {
		return array(
			'a' => array(
				'href' => array(),
				'title' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'p' => array(),
			'h2' => array(
				'id' => array(),
				'class' => array(),
			),
			'h3' => array(),
			'section' => array(
				'id' => array(),
				'class' => array(),
			),
			'nav' => array(),
		);
	}

}
