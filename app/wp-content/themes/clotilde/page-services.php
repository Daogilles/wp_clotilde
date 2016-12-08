<?php
/*
Template Name: Services
*/

get_header();
\F\JSONRenderer::getInstance()->pageType = 'services';

$pagename = get_query_var('pagename');

$highlight = get_field('highlight');

$type = 'service';
$args=array(
	'post_type' 		=> $type,
	'post_status' 		=> 'publish',
	'posts_per_page'    => '-1',
	'order'   			=> 'DESC'
);

$my_query = null;
$my_query = new WP_Query($args);    

?>

	<div id="services-page">
		<div id="highlight" class="full-vertical" style="background:url('<?php echo $highlight['url']; ?>') center center no-repeat;background-size:cover;z-index:10;">
			<img src="<?php echo $highlight['url']; ?>" />
			<span class="scroll-down">Scroll down</span>
		</div>
	
		<div class="services-wrapper">
			<?php if( $my_query->have_posts() ) {
	  			while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<div class="service-article">
						<div class="service-text-wrapper slider-container">
							<?php
							$contenu = get_field('contenu');
							$len = count($contenu)-1;
							foreach ($contenu as $key => $content) { ?>
								<div class="service-text slide item<?php echo $key+1; if($key == 0){?> active<?php } ?>">
									<div class="service-text-inner">
										<h3><?php echo $content['titre']?></h2>
										<p><?php echo $content['texte']?></p>
									</div>
									<?php if($key == 0){ ?>
										<span class="service-text-next service-text-arrow" data-next="<?php echo $key+2; ?>">NEXT</span>
									<?php }else if($key == $len) { ?>
										<span class="service-text-prev service-text-arrow" data-prev="<?php echo $key; ?>">PREV</span>
									<?php }else{ ?>
										<span class="service-text-prev service-text-arrow" data-prev="<?php echo $key; ?>">PREV</span>
										<span class="service-text-next service-text-arrow" data-next="<?php echo $key+2; ?>">NEXT</span>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
						<div class="service-img-wrapper slider-container">
							<?php
							$gallerie = get_field('gallerie');
							foreach ($gallerie as $key => $img) { ?>
								<div class="service-img" style="background:url(<?php echo $img['url']; ?>) top center no-repeat;background-size:cover;">
									<img src="<?php echo $img['url'];?>" alt="<?php echo $img['description'];?>" />
								</div>
								
							<?php } ?>

						</div>
					</div>
				<?php endwhile;
			}
			wp_reset_query();
			?>
		</div>
	</div>


<?php get_footer(); ?>
