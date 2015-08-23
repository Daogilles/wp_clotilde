<?php
/*
Template Name: Galerie
*/

get_header();
\F\JSONRenderer::getInstance()->pageType = 'gallery';

$pagename = get_query_var('pagename');

$highlight = get_field('highlight');
$images = get_field('images');

?>
	<div id="highlight"  style="background:url('<?php echo $highlight['url']; ?>') center center no-repeat;background-size:cover;z-index:10;">
		<img src="<?php echo $highlight['url']; ?>" id="highlight-img" />
		<span class="scroll-down">Scroll down</span>
	</div>

	<div class="gallery-wrapper">		
		<div class="gallery-container">
			<?php foreach ($images as $key => $img) { ?>
				<div class="gallery-img">
					<div class="gallery-img-inner" data-item="<?php echo $key; ?>">
						<img src="<?php echo $img['url']; ?>" alt="" />	
						<div class="gallery-img-opacity"></div>
						<span class="img-see">See more</span>
						<span class="img-plus">+</span>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="gallery-zoom">
		<div class="gallery-zoom-close"></div>
		<div class="gallery-zoom-inner">
			<div class="gallery-zoom-img">				
				<?php foreach ($images as $key => $img) { ?>					
					<img src="<?php echo $img['url']; ?>" alt="" data-item="<?php echo $key; ?>" />	
				<?php } ?>								
			</div>
			<div class="gallery-zoom-descr">
				<?php foreach ($images as $key => $img) { ?>
					<span data-item="<?php echo $key; ?>"><?php echo $img['description'] ?></span>
				<?php } ?>	
			</div>
			<div class="gallery-arrow">
				<div class="gallery-arrow-right">
					<span>NEXT</span>
				</div>
				<div class="gallery-arrow-left">
					<span>PREV</span>
				</div>
			</div>	
		</div>
	</div>

<?php get_footer(); ?>
