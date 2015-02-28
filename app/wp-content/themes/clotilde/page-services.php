<?php
/*
Template Name: Services
*/

get_header();
\F\JSONRenderer::getInstance()->pageType = 'services';

$pagename = get_query_var('pagename');

$highlight = get_field('highlight');


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
?>
	<ol id="pagination">
		<?php 
		$contenu = get_field('contenu');
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
	</ol>

	<div id="<?php echo $pagename; ?>-page" class="content-area content-page">
		<div id="wrapper-inner">
			<div id="highlight" class="full-vertical-gallery scroll" style="background:url('<?php echo $highlight['url']; ?>') center center no-repeat;background-size:cover;z-index:10;">
				<img src="<?php echo $highlight['url']; ?>" />
			</div>
			<?php 
			$contenu = get_field('contenu');
			foreach ($contenu as $key => $content) { ?>
				<div class="ligne scroll" style="z-index:<?php echo ($key+2) * 10; ?>;">
					<div class="services-img-wrap">
						<div class="services-img" style="background:url('<?php echo $content['gallerie']['url']; ?>') center center no-repeat;background-size:cover;">
							<img src="<?php echo $content['gallerie']['url'];?>" alt="<?php echo $content['gallerie']['description'];?>" />
						</div>
					</div>
					<div class="services-descr">
						<div class="services-descr-wrapper">
							<h2><?php echo $content['titre'];?></h2>
							<p><?php echo $content['texte'];?></p>
						</div>
					</div>
				</div>
			<?php } ?>
			<a href="#" class="arrow"></a>
		</div>
	</div>

	<div id="services-mobile">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut quis ligula vel turpis ornare rutrum. Donec mi ex, condimentum et lorem id, malesuada ullamcorper leo. Phasellus fringilla tempus maximus. Nulla semper dapibus ipsum sagittis egestas. Fusce efficitur velit id consectetur molestie. Sed quis nibh ultricies, imperdiet orci non, accumsan eros. Ut consectetur fringilla libero at mattis. Aenean ut justo vel lacus porta dictum molestie vitae nisi. In hac habitasse platea dictumst. Donec et felis sit amet purus vulputate porta nec quis nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
	</div>

<?php get_footer(); ?>
