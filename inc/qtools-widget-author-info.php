<?php
// qTools Widget:
// Latest from the same author


// Widget Registration
add_action( 'widgets_init', create_function( '', 'return register_widget("qToolsAuthorInfo");' ) );


// Widget Class
if ( ! class_exists( 'qToolsAuthorInfo' ) ) {
	class qToolsAuthorInfo extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'qtools_author_info', // Base ID
				__( 'qTools: Author Info', 'qtools' ), // Name
				array( 'description' => __( 'Post author info box', 'qtools' ), ) // Args
			);
		}

		public function widget( $args, $instance ) {
			global $authordata, $post;

			if ( is_single() ) {
				$author_ID      = $authordata->ID;
				$author_name    = get_the_author_meta( 'display_name', $author_ID );
				$author_bio     = get_the_author_meta( 'description', $author_ID );
				$author_website = get_the_author_meta( 'user_url', $author_ID );

				echo $args['before_widget'];

				if ( $instance['title'] ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
				}
				?>
				<div class="qtools-author-box<?php if ( $instance['boxed'] ) { echo ' boxed'; } ?>">
				<?php if ( $instance['gravatar'] ) { ?>
					<div class="qtools-author-avatar"><a href="<?php echo esc_url( get_author_posts_url( $author_ID ) ); ?>"><?php echo get_avatar( $author_ID, 200, 'mm', get_the_author_meta( 'display_name', $author_ID ) ); ?></a></div>
				<?php } 
				if ( $instance['name'] ) { ?>
					<h3 class="qtools-author-name"><a href="<?php echo esc_url( get_author_posts_url( $author_ID ) ); ?>"><?php echo esc_html( $author_name ); ?></a></h3>
				<?php } 
				if ( $instance['bio'] && $author_bio ) { ?>
					<div class="qtools-author-bio"><?php echo esc_html( $author_bio ); ?></div>
				<?php } 
				if ( $instance['website'] && $author_website ) { ?>
					<div class="qtools-author-links"><a href="<?php echo esc_html( $author_website ); ?>" class="small-title link"><?php printf( esc_html__( "%s's Website", 'qtools' ), the_author_meta( 'display_name', $author_ID ) ); ?></a></div>
				<?php } ?>
				</div>
				<?php
				echo $args['after_widget'];

			}
		}

		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => __( 'Post Author', 'qtools' ) ) );
			$title    = sanitize_text_field( $instance['title'] );
			$gravatar = isset( $instance['gravatar'] ) ? (bool) $instance['gravatar'] : true;
			$bio      = isset( $instance['bio'] ) ? (bool) $instance['bio'] : true;
			$website  = isset( $instance['website'] ) ? (bool) $instance['website'] : true;
			$boxed    = isset( $instance['boxed'] ) ? (bool) $instance['boxed'] : false;
			?>
			<pre style="max-width: 100%; white-space: pre-wrap; background-color: #faf0f0; padding: 10px;"><?php esc_html_e( 'This widget will only show when a user is on a single post page.', 'qtools' ); ?></pre>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e( 'Widget title:', 'qtools' ); ?></strong></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<strong style="display: block;"><?php _e( 'Author Gravatar:', 'qtools' ); ?></strong>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'gravatar' ); ?>" name="<?php echo $this->get_field_name( 'gravatar' ); ?>"<?php checked( $gravatar ); ?> /> <label for="<?php echo $this->get_field_id( 'gravatar' ); ?>"><?php _e( 'Display Gravatar?', 'qtools' ); ?></label>
			</p>
			<p>
				<strong style="display: block;"><?php _e( 'Author Bio:', 'qtools' ); ?></strong>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'bio' ); ?>" name="<?php echo $this->get_field_name( 'bio' ); ?>"<?php checked( $bio ); ?> /> <label for="<?php echo $this->get_field_id( 'bio' ); ?>"><?php _e( 'Display bio?', 'qtools' ); ?></label>
			</p>
			<p>
				<strong style="display: block;"><?php _e( 'Author Website:', 'qtools' ); ?></strong>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'website' ); ?>" name="<?php echo $this->get_field_name( 'website' ); ?>"<?php checked( $website ); ?> /> <label for="<?php echo $this->get_field_id( 'website' ); ?>"><?php _e( 'Display website link?', 'qtools' ); ?></label>
			</p>
			<p>
				<strong style="display: block;"><?php _e( 'Boxed display:', 'qtools' ); ?></strong>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'boxed' ); ?>" name="<?php echo $this->get_field_name( 'boxed' ); ?>"<?php checked( $boxed ); ?> /> <label for="<?php echo $this->get_field_id( 'boxed' ); ?>"><?php _e( 'Add box around info?', 'qtools' ); ?></label>
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
			$instance['title']    = sanitize_text_field( $new_instance['title'] );
			$instance['gravatar'] = ! empty( $new_instance['gravatar'] ) ? 1 : 0;
			$instance['bio']      = ! empty( $new_instance['bio'] ) ? 1 : 0;
			$instance['website']  = ! empty( $new_instance['website'] ) ? 1 : 0;
			$instance['boxed']    = ! empty( $new_instance['boxed'] ) ? 1 : 0;

			return $instance;
		}
	}
}
?>