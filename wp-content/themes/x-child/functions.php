<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to X in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );

// Additional Functions
// =============================================================================
function theme_padmanaba() {
	$parent_style = 'parent-style';
	wp_enqueue_style( 'style-bootstrap', get_stylesheet_directory_uri().'/assets/css/bootstrap.min.css' );
	wp_enqueue_script( 'script-padmanaba', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'script-padmanaba-2', get_stylesheet_directory_uri() . '/assets/js/bootstrap.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'theme_padmanaba' );
add_action( 'wp_enqueue_scripts', 'rv_enqueue_slick' );
function rv_enqueue_slick() {

	wp_enqueue_script( 'slick-js', '//cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js', 'jquery', '1.4.1' );

	wp_enqueue_script( 'slick-init-js', trailingslashit( get_stylesheet_directory_uri() ) . 'assets/js/slick-init.js', 'jquery', 1.0 );

	wp_enqueue_style( 'style-slick', get_stylesheet_directory_uri().'/assets/css/slick.css' );
}
// Add Shortcode
function recent_posts_function($atts){
	extract(shortcode_atts(array(
		'posts' => 3,
		), $atts));

	$return_string = '<div class="post-list-home"><ul>';
	query_posts(array('orderby' => 'date', 'order' => 'DESC' , 'showposts' => $posts));
	if (have_posts()) :
		while (have_posts()) : the_post();
	$return_string .= 	'
	<li>
		<div class="wrap-post-list">
		<div class="img-list">
				<a href="'.get_permalink().'">'.get_the_post_thumbnail($post_id, 'medium' ).'</a>
			</div>
			<div class="title-list">
				<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>
				<div class="date-time">'.get_the_date('g:i:s, d F Y').'</div>
			</div>
		</div>
	</li>';
	endwhile;
	endif;
	$return_string .= '</ul></div>';

	wp_reset_query();
	return $return_string;
}
add_shortcode('recent-posts', 'recent_posts_function');

function get_alumni(){
	$args = array(
		'post_type' => 'alumni',
		'post_status' => 'publish',
		'posts_per_page' => 8,
		'tax_query' => array(
				array(
					'taxonomy' => 'featured',
					'field' => 'slug',
					'terms' => 'featured', 
					'include_children' => false
					    )
				)
		);
	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ){
		$string .= '<div class="row list-alumni-box">';
		while( $query->have_posts() ){
			$query->the_post();
			global $post;
			$angkatan = get_the_terms($post->ID,'angkatan');
			$_angkatan = array();
			foreach ($angkatan as $key) 
			{
				$_angkatan[] = $key->name;
			}
				$string .='<div class="col-md-3 col-sm-6 col-xs-6 grid-box">
							<div class="img-box">
								<a href="'.get_permalink().'">'.get_the_post_thumbnail($post_id, 'medium' ).'
									</div><div class="title-box"><h5>' .get_the_title() . '</h5>
									</a>
									</div>
									<div class="kategori-box">Angkatan '.implode(',' , $_angkatan).'
									</div>
									</div>';
		}
		$string .= '</div>';
	}

	wp_reset_query();
	return $string;
}
add_shortcode( 'alumni_list_frontpage', 'get_alumni' );




function get_bisnis(){
	$args = array(
		'post_type' => 'business',
		'post_status' => 'publish',
		'posts_per_page' => 8,
		'tax_query' => array(
				array(
					'taxonomy' => 'featured',
					'field' => 'slug',
					'terms' => 'featured', 
					'include_children' => false
					    )
				)
		);

	$string = '';
	$query = new WP_Query( $args );


	if( $query->have_posts() ){
		$string .= '<div class="row list-bisnis-box">';
		while( $query->have_posts() ){ /* looping pertama*/
			$query->the_post();
			global $post;
			$kategori = get_the_terms($post->ID, 'kategori');
			$_kategori = array();
			foreach($kategori AS $cat)
			{
				$_kategori[] =  $cat->name;
			}

			// print_r($angkatan);
				$string .='<div class="col-md-3 col-sm-6 col-xs-6 grid-box">
							<div class="img-box">
								<a href="'.get_permalink().'">'.get_the_post_thumbnail($post_id, 'medium' ).'
									</div><div class="title-box"><h5>' .get_the_title() . '</h5>
									</a>
									</div>
									<div class="kategori-box">'.implode(',' , $_kategori).'
									</div>
									</div>';
	
		}
		$string .= '</div>';
	}
	wp_reset_query();
	return $string;
}
add_shortcode( 'bisnis_list_frontpage', 'get_bisnis' );

function angkatan_dropdown( $taxonomy, $orderby = 'date', $order = 'DESC', $limit = '-1', $name, $show_option_all = null, $show_option_none = null ) {
	$args = array(
		'orderby' => $orderby,
		'order' => $order,
		'number' => $limit,
		'selected' => $name
	);
	$terms = get_terms( $taxonomy, $args );



	$name = ( $name ) ? $name : $taxonomy;
	if ( $terms ) {
		printf( '<select name="%s" class="postform form-control">', esc_attr( $name ) );
		if ( $show_option_all ) {
			printf( '<option value="?term=">%s</option>', esc_html( $show_option_all ) );
		}
		foreach ( $terms as $term ) {
			$selected = "";

			if(isset($_GET['term'])){
				$selectedTerm = $_GET['term'];

				if($selectedTerm==$term->slug)
					$selected='selected';
			}

			printf( '<option value="?term=%s" %s >%s</option>', esc_attr( $term->slug ),$selected,esc_html($term->name ) );
		}
		print( '</select>' );
	}
}
function kategori_dropdown( $taxonomy, $orderby = 'date', $order = 'DESC', $limit = '-1', $name, $show_option_all = null, $show_option_none = null ) {
	$args = array(
		'orderby' => $orderby,
		'order' => $order,
		'number' => $limit,
		'selected' => $name
	);
	$terms = get_terms( $taxonomy, $args );
	$name = ( $name ) ? $name : $taxonomy;
	if ( $terms ) {
		printf( '<select name="%s" class="postform">', esc_attr( $name ) );
		if ( $show_option_all ) {
			printf( '<option value="0">%s</option>', esc_html( $show_option_all ) );
		}
		foreach ( $terms as $term ) {
			printf( '<option value="?term=%s">%s</option>', esc_attr( $term->slug ), esc_html($term->name ) );
		}
		print( '</select>' );
	}
}
function home_pagesize( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_home() ) {
        // Display only 1 post for the original blog archive
        $query->set( 'posts_per_page', 1 );
        return;
    }

    if ( is_post_type_archive( 'alumni' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'posts_per_page', 4 );
        return;
    }
     if ( is_post_type_archive( 'business' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'posts_per_page', 4 );
        return;
    }
}
add_action( 'pre_get_posts', 'home_pagesize', 1 );

add_action( 'genesis_after_content', 'rv_output_slick_slider' );
function rv_output_slick_slider() {

	echo '<div class="single-item">';

	if ( have_rows( 'slick_slides' ) ) :

		while ( have_rows( 'slick_slides' ) ) : the_row();

			$image = get_sub_field( 'image' );

			$image_url = $image['url'];

			echo '<div class="single-item">';

				printf( '<img src="%s" />', $image_url );

			echo '</div>';

		endwhile;

	endif;

	echo '</div>';

}






