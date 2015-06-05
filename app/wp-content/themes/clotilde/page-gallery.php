<?php
/*
Template Name: Galerie
*/

get_header();
\F\JSONRenderer::getInstance()->pageType = 'gallery';

$pagename = get_query_var('pagename');

$highlight = get_field('highlight');
$images = get_field('images');


	/*<ol id="pagination">
		<li class="no-margin item-active">
			<div class="active pagination-wrapper">
				<div class="pagination-wrapper-img">
					<div style="background:url(<?php echo $highlight['url']; ?>) center center no-repeat;background-size:cover;"></div>
				</div>
			</div>
			<a href="#" class="pagination-bg-black"></a>
		</li>
		<?php foreach ($images as $key => $img) { ?> 
			<li>
				<div class="pagination-wrapper">
					<div class="pagination-wrapper-img">
						<div style="background:url(<?php echo $img['url']; ?>)center center no-repeat;background-size:cover;"></div>
					</div>
				</div>
				<a href="#" class="pagination-bg-black"></a>
			</li>
		<?php } ?>
	</ol>*/
?>
	<div class="controls-aside">
		<ol id="pagination">
			<?php foreach ($images as $key => $img) {
				$index = $key;
			}
			if(!empty($highlight)){
				$index = $index +2;
			}else{
				$index = $index +1;
			}
			?>
			<div id="pag_number">1/<?php echo $index; ?></div>
		</ol>
		<div class="btn-gallery">
			<span class="open-gallery"></span>
			<span class="open-gallery state-hover"></span>
		</div>		
	</div>
	
	<div class="gallery-wrapper">
		<div class="gallery-opacity"></div>
		<span class="close-gallery"></span>
		<div class="gallery-container">
			<?php foreach ($images as $key => $img) { ?>
				<div class="gallery-img">
					<div class="gallery-img-inner">
						<img src="<?php echo $img['url']; ?>" alt="" />	
					</div>					
				</div>
			<?php } ?>
		</div>
	</div>

	<div id="<?php echo $pagename; ?>-page" class="content-area content-page">
		<div id="wrapper-inner">
			<?php if (!empty($highlight)){ ?>
			<div id="highlight" class="scroll" style="background:url('<?php echo $highlight['url']; ?>') center center no-repeat;background-size:cover;z-index:10;">
				<img src="<?php echo $highlight['url']; ?>" />
				<a href="#" class="arrow"></a>
			</div>
			<?php } ?>
			<?php foreach ($images as $key => $img) { ?>
				<!-- <div id="gallery-wrapper" class="scroll"> -->
					<div class="scroll" style="z-index:<?php echo ($key+2) * 10; ?>;">
						<img src="<?php echo $img['url']; ?>" />
					</div>
				<!-- </div> -->
			<?php } ?>

		</div>
	</div><!-- #primary -->

<?php get_footer(); ?>
