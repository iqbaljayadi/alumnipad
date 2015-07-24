<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
		$idSekarang = get_the_ID();
			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="container">
					<div class="row single-bisnis">
						<div class="col-md-4">
							<div class="image-single-business">
								
								<?php rv_output_slick_slider(); ?>

							</div>
						</div>
						<div class="col-md-8">
							<div class="kategori-single">
								<h6>
									<?php $kategori = get_the_terms($post->ID, 'kategori');
									foreach ($kategori as $cats => $cat) {	 
										echo  $cat->name;
									}?>
								</h6>
							</div>
							<div class="title-single">
								<h4><?php the_title();?></h4>
							</div>	
							<div class="isi-content">
								<div class="label-karir">Deskripsi bisnis</div>
								<?php 
								the_content(); 
								?>
							</div>
							<div class="link-text">
								<div class="label-karir">Link Website</div>
								<?php 
								$link = get_post_custom_values( 'link_ke_website' );
								foreach ( $link as $key => $value ) {
									echo $value; 
								}
								?>
							</div>
							<div class="buton-link">
								<a href="<?php echo $value; ?>"  target="_blank" class="btn btn-default"> Klick Website</a>
							</div>
						</div>	
					</div>
				</div>


				<?php
				// echo '<pre>';
				// print_r($kategori);
				// exit();
				$terms = get_terms( 'kategori' );
				$term_ids = wp_list_pluck( $terms, 'term_id' );

				if(sizeof($kategori)!=0):

				$term_id = ( $kategori[0]->count > 1 ) ? $kategori[0]->term_id : $term_ids;

				$args = array(
					'post_type' => 'business',
					'posts_per_page'=>4, 
					'order'=>'ASC',
					'tax_query' => array(
						array(
							'taxonomy' => 'kategori',
							'field' => 'term_id',
						    'terms' => $term_id, // Where term_id of Term 1 is "1".
						    'include_children' => false
						    )
						)
					);

				$bisnis = new WP_Query( $args );

				if ( $bisnis->have_posts() ) : ?>
				<div class="container">
					<div class="grid-wrapper">
						<h4 class="panel-title text-center">Bisnis Sejenis</h4>
						<div class="row">

							<?php while ( $bisnis->have_posts() ) : $bisnis->the_post(); ?>

								<?php
						// tidak menampilkan dirinya sendiri
								if(get_the_ID()==$idSekarang)
									continue;
								?>

								<div class="col-md-3 col-sm-6 grid-box">
									<a href="<?php the_permalink(); ?>">
										<div class="archive-box">
											<?php 
											if ( has_post_thumbnail() ) { 
												the_post_thumbnail($post_id, 'medium' ); 
											}
											?>
											<div class="title-box text-center">
												<a href="<?php the_permalink(); ?>">
													<h5><?php the_title(); ?></h5>
												</a>
											</div>
										</div>
									</a>
									<div class="kategori text-center">
										<?php $kategori = get_the_terms($post->ID, 'kategori');
										foreach ($kategori as $key => $value) {	 
											echo  $value->name;
										}
										?>
									</div>
								</div>
							<?php endwhile; // end of the loop. ?>
						</div>
					</div>
				</div>
				
				<div class="button-berikutnya text-center">
					<a href="http://syihab-pc.local.tonjoo.com/alumni-padmanaba/business" class="btn btn-default"> Profil Bisnis Lainnya </a>
				</div>

			</div><!-- .content-area -->
		
	<?php endif; ?>

<?php endif; ?>
</article>
<?php 	
			// If comments are open or we have at least one comment, load up the comment template.
endwhile;
?>

</main><!-- .site-main -->
<?php get_footer(); ?>
