<?php 
get_header();

?>
<div class="page-heading">
	<div class="row">
		<div class="col-md-6 col-sm-6 ">
			<h2 class="title-page">Galeri Bisnis</h2>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
			<?php angkatan_dropdown( 'kategori', 'date', 'DESC', '','kategori', 'Select All' , ''); ?>
		</div>
	</div>
</div>
<div class="container main-content">

	<?php 

	if ($_GET['term']){
		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$args = array(
			'post_type' => 'business',
			'posts_per_page'=>12, 
			'order'=>'ASC',
			'paged' => $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'kategori',
					'field' => 'slug',
					    'terms' => $_GET['term'], // Where term_id of Term 1 is "1".
					    'include_children' => false
					    )

				));
	}
	else{
		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$args = array(
			'post_type'=>'business',
			'posts_per_page'=>12, 
			'order'=>'ASC',
			'paged' => $paged
			);
	}
	$bisnis = new WP_Query( $args );
	?>
	<div class="row">
		<?php while ( $bisnis->have_posts() ) : $bisnis->the_post(); ?>
			<div class="col-md-3 col-sm-6 grid-box">
				
				<div class="archive-box">
					<a href="<?php the_permalink(); ?>">
						<?php 
						if ( has_post_thumbnail() ) { 
							the_post_thumbnail($post_id, 'medium' ); 
						}
						?>
					</a>
					<div class="title-box">
						<h5>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h5>
					</div>
				</div>
				
				<div class="kategori text-center">
					<?php $kategori = get_the_terms($post->ID, 'kategori');
					foreach ($kategori as $key => $value) {	 
						echo  $value->name;
					}
					?>
				</div>
			</div>

		<?php endwhile;
		wp_reset_query();  // end of the loop. ?>

	</div>
	<div class="grid-paginat">
		<nav class="pagination">
			<?php
			global $bisnis;
						$big = 999999999; // need an unlikely integer
						$translated = __( 'Page', 'business' ); // Supply translatable string
						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $bisnis->max_num_pages
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