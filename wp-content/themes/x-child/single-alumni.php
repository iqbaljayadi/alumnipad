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
					<div class="row">
						<div class="col-md-4">
							<div class="image-single-alumni">
								<?php 
								if ( has_post_thumbnail( $post->ID ) ) {
									echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">';
									echo get_the_post_thumbnail( $post->ID, 'medium' );
									echo '</a>';
								}
								?>
							</div>
						</div>
						<div class="col-md-8">
							<div class="angkatan-single">
								<h6>
									<?php  
									$terms = get_the_terms( $post->ID , 'angkatan' );	
									if ( $terms != null ){
										foreach( $terms as $term ) {	 
											echo 'Angkatan '.$term->name ;	
											// unset($term);
										} 
									} 
									?>
								</h6>
							</div>
							<div class="title-single">
								<h4><?php the_title();?></h4>
							</div>	
							<div class="domisili-single">
								<div class="label-domisili">DOMISILI</div>
								<?php
								$domisili = get_the_terms($post->ID , 'city');
								if ( $domisili!= null ){
									foreach ($domisili as $key) {
										echo  $key->name;
									}
								}
								?>
							</div>
							<div class="karir-text">
								<div class="label-karir">KARIR</div>
								<?php 
								$karir = get_post_custom_values( 'karir' );
								foreach ( $karir as $key => $value ) {
									echo $value; 
								}
								?>
							</div>
							<div class="hobi-text">
								<div class="label-hoby">HOBY</div>
								<?php 
								$hobi = get_post_custom_values( 'hobi' );
								foreach ( $hobi as $key => $value ) {
									echo $value; 
								}
								?>
							</div>
							<div class="biografi-text">
								<div class="label-biografi">BIOGRAFI</div>
								<p>
									<?php 
									$biografi = get_post_custom_values( 'biografi' );
									foreach ( $biografi as $key => $value ) {
										echo $value; 
									}
									?>
								</p>
							</div>
						</div>	
					</div>
				</div>
			</article>
			<?php 	
			// If comments are open or we have at least one comment, load up the comment template.
			endwhile;
			?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<?php
	if(sizeof($terms)!=0):

		$term_id = $terms[0]->term_id;

	$args = array(
		'post_type' => 'alumni',
		'posts_per_page'=>4, 
		'order'=>'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'angkatan',
				'field' => 'term_id',
						    'terms' => $term_id, // Where term_id of Term 1 is "1".
						    'include_children' => false
						    )
			)
		);

	$alumni = new WP_Query( $args );



	if ( $alumni->have_posts() ) : ?>

	<div class="container">
		<div class="grid-wrapper">
			<h4 class="panel-title text-center">Rekan Seangkatan</h4>
			<div class="row">
				<?php while ( $alumni->have_posts() ) : $alumni->the_post(); ?>

					<?php
						// tidak menampilkan dirinya sendiri
					if(get_the_ID()==$idSekarang)
						continue;
					?>
					<div class="col-md-3 col-sm-6 grid-box">
						<div class="archive-box">
							<a href="<?php the_permalink(); ?>">
								<?php 
								if ( has_post_thumbnail() ) { 
									the_post_thumbnail($post_id, 'medium' ); 
								}
								?></a>
								<div class="title-box">
									<h5>
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?></a>
										</h5>
									</div>
								</div>
							</a>
							<div class="terms-angkatan">
								<?php $angkatan = get_the_terms($post->ID, 'angkatan');
								$_angkatan = array();
								foreach ($angkatan as $key) 
								{
									$_angkatan[] = $key->name;
								}
								echo implode(',' , $_angkatan);
								?>
							</div>
						</div>
					<?php endwhile; // end of the loop. ?>
				</div>

				<div class="button-berikutnya text-center">
					<a href="http://syihab-pc.local.tonjoo.com/alumni-padmanaba/alumni" class="btn btn-default"> Profil Alumni Lainnya </a>
				</div>
			</div>
		</div>
	</div>


<?php endif; ?>

<?php endif; ?>

<?php get_footer(); ?>
