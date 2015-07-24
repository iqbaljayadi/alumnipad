<?php 
get_header();

?>
<div class="page-heading">
	<div class="row">
		<div class="col-md-6 col-sm-6 ">
			<h2 class="title-page">Profil Alumni</h2>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
			<?php angkatan_dropdown( 'angkatan', 'date', '','DESC', 'angkatan', 'Select All', 'Select None' ); ?>
		</div>
	</div>
</div>
<div class="container main-content">
	<?php 

	if ($_GET['term']){
		if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
		elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
		else { $paged = 1; }
		$args = array(
			'post_type' => 'alumni',
			'posts_per_page'=>12, 
			'order'=>'ASC',
			'paged' => $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'angkatan',
					'field' => 'slug',
					    'terms' => $_GET['term'], // Where term_id of Term 1 is "1".
					    'include_children' => false
					    )
				)
			);
	}
	else {
		if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
		elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
		else { $paged = 1; }
		$args = array(
			'post_type' => 'alumni',
			'posts_per_page'=>12, 
			'order'=>'ASC',
			'paged' => $paged,
			);
	}
	$alumni = new WP_Query( $args );
	?>

	<div class="row">
		<?php while ( $alumni->have_posts() ) : $alumni->the_post(); ?>
			<div class="col-md-3 col-sm-6 grid-box">
				
				<div class="archive-box">
				<a href="<?php the_permalink(); ?>">
					<?php 
					if ( has_post_thumbnail() ) { 
						the_post_thumbnail($post_id, 'medium' ); 
					}
					?></a>
					<div class="title-box">
						<h5><a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a></h5>
					</div>
				</div>
				<div class="terms-angkatan">
					<?php $angkatan = get_the_terms($post->ID, 'angkatan');
					$_angkatan = array();
					foreach ($angkatan as $key) 
					{
						$_angkatan[] = $key->name;
					}
					echo 'Angkatan '.implode(',' , $_angkatan);
					?>
				</div>
			</div>
		<?php endwhile; // end of the loop.?>
	</div>
	<div class="pagination pagination-centered">
		<nav class="pagination pagination-centered">

			<?php
			global $alumni;
					$big = 999999999; // need an unlikely integer
					$translated = __( 'Page', 'alumni' ); // Supply translatable string
					echo paginate_links( array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' => '?paged=%#%',
						'current' => max( 1, get_query_var('paged') ),
						'total' => $alumni->max_num_pages
						) );
						?>
					</nav>
				</div>
			</div>
			<script>
				jQuery(document).ready( function() {
					jQuery('.postform').change( function() {
						location.href = jQuery(this).val();
					});
				});
			</script>

			<?php 
			get_footer();

			?>