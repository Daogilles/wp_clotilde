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


/*	<ol id="pagination">
		<li class="no-margin item-active">
			<div class="active pagination-wrapper">
				<div class="pagination-wrapper-img">
					<div style="background:url(<?php echo $highlight['url']; ?>) center center no-repeat;background-size:cover;"></div>
				</div>
			</div>
			<a href="#" class="pagination-bg-black"></a>
		</li>
		<?php 
			$contenu = get_field('contenu');
			foreach ($contenu as $key => $content) { ?>
			<li>
				<div class="pagination-wrapper">
					<div class="pagination-wrapper-img">
						<?php foreach ($content['gallerie'] as $img) { ?>
							<div style="background:url(<?php echo $img['url']; ?>)center center no-repeat;background-size:cover;"></div>
						<?php } ?>
					</div>
				</div>
				<a href="#" class="pagination-bg-black"></a>
			</li>
		<?php } ?>
	</ol>
*/

	/*<ol id="pagination">
		<?php 
		
		foreach ($contenu as $key => $content) {
			$index = $key;
		}
		if(!empty($highlight)){
			$index = $index +2;
		}else{
			$index = $index +1;
		}
		?>
		<div id="pag_number">1/<?php echo $index; ?></div>
	</ol>*/
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
										<span class="service-text-next service-text-arrow" data-next="<?php echo $key+2; ?>"></span>
									<?php }else if($key == $len) { ?>
										<span class="service-text-prev service-text-arrow" data-prev="<?php echo $key; ?>"></span>
									<?php }else{ ?>
										<span class="service-text-prev service-text-arrow" data-prev="<?php echo $key; ?>"></span>
										<span class="service-text-next service-text-arrow" data-next="<?php echo $key+2; ?>"></span>
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
