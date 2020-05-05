<?php

 
/********************************************
* Creating posts by tags
**********************************************/
 
class Iqlasit_Posts_by_tags extends WP_Widget {

    function __construct() {

        parent::__construct(
            // Base ID of your widget
            'iqlasit_posts_by_tags',

            // Widget name will appear in UI
            esc_html__( 'Iqlasit - Posts By Tags', 'iqlasit-posts-by-tags' ),

            // Widget description
            array( 'description' => esc_html__( 'Show Posts By Tags', 'iqlasit-posts-by-tags' ), )
        );

    }

    // This is where the action happens
    public function widget( $args, $instance ) {
        $title 	  = apply_filters( 'widget_title', $instance['title'] );

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];

        $tags = get_tags(array());
        
        $output .= '<ul class="iqlasit-tag-list">';
            if($tags) {
            foreach ($tags as $tag):
            $output .= '<li><span class="single-tag" data-tagid="'. esc_attr($tag->term_id) .'">'. esc_attr($tag->name) .'</span></li>';
            endforeach;
            } else {
            _e('No tags created yet!', 'iqlasit-posts-by-tags');
            }
        $output .= '</ul>';
        echo $output;
        
        echo $args['after_widget'];
    }
            
    // Widget Backend 
    public function form( $instance ) {
        
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }else {
            $title = esc_html__( 'Posts By Tags', 'iqlasit-posts-by-tags' );
        }
        

        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ,'iqlasit-posts-by-tags'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php 
    }
        
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }
} // Class Iqlasit_Posts_by_tags ends here


// Register and load the widget
function iqlasit_posts_by_tags() {
	register_widget( 'Iqlasit_Posts_by_tags' );
}
add_action( 'widgets_init', 'iqlasit_posts_by_tags' );