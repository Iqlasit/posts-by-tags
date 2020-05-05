<?php
/*
Plugin Name: Posts by Tags
Plugin URI: https://github.com/Iqlasit/posts-by-tags
Description: Posts by Tags plugin will display all posts
Version: 1.0.0
Author: Iqlasit
Author URI: http://iqlasit.com
License: GPLv2 or later
Text Domain: iqlasit-posts-by-tags
*/

// Include the widget file
include( plugin_dir_path( __FILE__ ) . "widget-file.php");

// Enqueueing assets
add_action( 'wp_enqueue_scripts', function () {
    wp_register_style( 'iqlasit-pbtgs-ajax-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
    wp_register_script( 'iqlasit-pbtgs-ajax-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
    wp_enqueue_style( 'iqlasit-pbtgs-ajax-css' );
    wp_enqueue_script( 'iqlasit-pbtgs-ajax-js' );
    wp_localize_script(
        'iqlasit-pbtgs-ajax-js',
        'iqlasit_pbtgs',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );

} );

// Ajax function
function iqlasit_ajax_func() {
    $tag_ids = isset( $_REQUEST['tag_ids'] ) ? (array) $_REQUEST['tag_ids'] : array();
    $tag_ids = array_map( 'intval', $tag_ids );
    set_transient( 'iqlasit_tag_ids', $tag_ids );

    $query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'tag__in'        => $tag_ids,
    );
    header('Content-Type: application/json');
    $custom_query = new WP_Query( $query_args );
    $result = [];
    while($custom_query->have_posts()) : $custom_query->the_post();
    $result[] = array(
        'id'                => get_the_ID(),
        'title'             => get_the_title(),
        'content'           => get_the_excerpt(),
    );
    endwhile;
    wp_reset_postdata();
    echo json_encode($result);
    wp_die();
}
add_action( 'wp_ajax_iqlasit_tag_info', 'iqlasit_ajax_func' );
add_action( 'wp_ajax_nopriv_iqlasit_tag_info', 'iqlasit_ajax_func' );

// Registering shortcode for displaying posts
function iqlasit_posts_by_tags_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'post_type'      => 'post',
        'posts_per_page' => '-1',
    ), $atts, 'iqlasit_posts_by_tags' );
    extract( $atts );
    if ( false === ( $iqlasit_tag_ids = get_transient( 'iqlasit_tag_ids' ) ) ) {
        $iqlasit_tag_ids = [];
    } else {
        $iqlasit_tag_ids = get_transient( 'iqlasit_tag_ids' );
    }
    
    $query_args = array(
        'post_type'      => sanitize_text_field($post_type),
        'posts_per_page' => intval($posts_per_page),
        'tag__in'        => $iqlasit_tag_ids,
    );
    $custom_query = new WP_Query( $query_args );
    echo '<div class="iqlasit-posts-by-tags-wrapper">';
    while($custom_query->have_posts()) : $custom_query->the_post();?>
        <div class="iqlasit-single-post-item">
            <h1><?php echo the_title();?></h1>
            <?php echo the_excerpt();?>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    echo '</div>';
}
add_shortcode( 'iqlasit_posts_by_tags', 'iqlasit_posts_by_tags_shortcode' );