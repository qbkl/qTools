<?php
// qTools Widget:
// Latest from posts


// Widget Registration
add_action( 'widgets_init', create_function( '', 'return register_widget("qToolsLatestPosts");' ) );


// Widget Class
if ( ! class_exists( 'qToolsLatestPosts' ) ) {
	class qToolsLatestPosts extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'qtools_latest_posts', // Base ID
			__( 'qTools: Latest Posts', 'qtools' ), // Name
			array( 'description' => __( 'Latest posts from blog', 'qtools' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		$latest_args = array(
			'numberposts' => $instance['posts_no'],
			'post_status' => 'publish'
		);

		$latest_posts = wp_get_recent_posts( $latest_args );		
		
		if ( $latest_posts ) {
			echo '<ul>';

			foreach( $latest_posts as $latest ) {
				$post_categories = get_the_category( $latest['ID'] );
				
				$post_categories_output = array();
				
				foreach( $post_categories as $category ){
					$category_output = '<a href="' . esc_url( get_category_link( $category->cat_ID ) ) . '">' . esc_html( $category->name ) . '</a>';
					
					array_push( $post_categories_output, $category_output );
				}
				
				if ( count( $post_categories_output ) > 0 ) {
					$post_categories_output = ' ' . __( 'in', 'qtools' ) . ' ' . implode( ', ', $post_categories_output ) . ' ';
				} else {
					$post_categories_output = '';
				}
				
				echo '<li>';
				
				if ( $instance['thumbs'] && current_theme_supports( 'post-thumbnails' ) ) {
					echo '<div class="qtools-widget-post-thumb">';
					
					if ( has_post_thumbnail( $latest['ID'] ) ) {
						echo '<a href="' . esc_url( get_permalink( $latest['ID'] ) ) . '">' . get_the_post_thumbnail( $latest['ID'], 'thumbnail' ) . '</a>';
					} else {
						echo '<a href="' . esc_url( get_permalink( $latest['ID'] ) ) . '" title="' . apply_filters( 'the_title_attribute', $latest['post_title'] ) . '"><img src="' . plugin_dir_url( dirname( __FILE__) ) . 'img/no-thumb.png" alt="' . apply_filters( 'the_title_attribute', $latest['post_title'] ) . '"></a>';
					}

					echo '</div>';
				}

				echo '<div class="qtools-widget-post-info">';
				echo '<h4 class="qtools-widget-post-title"><a href="' . esc_url( get_permalink( $latest['ID'] ) ) . '">' . apply_filters( 'the_title', $latest['post_title'] ) . '</h4></a>';
				
				if ( $instance['meta'] ) {
					echo '<div class="qtools-widget-post-meta"><a href="' . esc_url( get_author_posts_url( $latest['post_author'] ) ) . '">' . esc_html ( get_the_author_meta( 'display_name', $latest['post_author'] ) ) . '</a> ' . $post_categories_output . '</div>';
				}

				echo '</div>';
				echo '</li>';
			}

			echo '</ul>';
		} else {
			esc_html_e('No posts found!', 'qtools');
		}

		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __( 'Latest content', 'qtools' ) ) );
		$title    = sanitize_text_field( $instance['title'] );
		$posts_no = ! empty( $instance['posts_no'] ) ? absint( sanitize_text_field( $instance['posts_no'] ) ) : 2;
		$thumbs	  = isset( $instance['thumbs'] ) ? (bool) $instance['thumbs'] : false;
		$meta     = isset( $instance['meta'] ) ? (bool) $instance['meta'] : true;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e( 'Widget title:', 'qtools' ); ?></strong></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label style="display: block;" for="<?php echo $this->get_field_id( 'posts_no' ); ?>"><strong><?php _e( 'Number of posts (1-8):', 'qtools' ); ?></strong></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'posts_no' ); ?>" name="<?php echo $this->get_field_name( 'posts_no' ); ?>" type="number" min="1" max="8" value="<?php echo absint( esc_attr( $posts_no ) ); ?>">
		</p>
		<p>
			<strong style="display: block;"><?php _e( 'Thumbnails:', 'qtools' ); ?></strong>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs' ); ?>" name="<?php echo $this->get_field_name( 'thumbs' ); ?>"<?php checked( $thumbs ); ?> /> <label for="<?php echo $this->get_field_id( 'thumbs' ); ?>"><?php _e( 'Display thumbnails?', 'qtools' ); ?></label>
		</p>
		<p>
			<strong style="display: block;"><?php _e( 'Post meta:', 'qtools' ); ?></strong>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>"<?php checked( $meta ); ?> /> <label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e( 'Display author &amp; category?', 'qtools' ); ?></label>
		</p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {

		$check_min = ( 1 <= absint( strip_tags( $new_instance['posts_no'] ) ) ) ? true : false;
		$check_max = ( absint( strip_tags( $new_instance['posts_no'] ) ) <= 8 ) ? true : false;
		
		if ( ! $check_min ) {
			$new_instance['posts_no'] = 1;
		} else if ( ! $check_max ) {
			$new_instance['posts_no'] = 8;
		} else {
			$new_instance['posts_no'] = absint( strip_tags( $new_instance['posts_no'] ) );
		}
		
		$instance             = $old_instance;
		$instance['title']    = sanitize_text_field( $new_instance['title'] );
		$instance['posts_no'] = ! empty( $new_instance['posts_no'] ) ? sanitize_text_field( $new_instance['posts_no'] ) : 2;
		$instance['thumbs']   = ! empty($new_instance['thumbs']) ? 1 : 0;
		$instance['meta']     = ! empty($new_instance['meta']) ? 1 : 0;

		return $instance;
	}
}
}
?>