<?php
/*
Template Name: Contact
*/

get_header();
\F\JSONRenderer::getInstance()->pageType = 'contact';

$pagename = get_query_var('pagename');

$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('texte');

?>
	<div id="contact" class="full-vertical">
        <div id="contact-wrapper">
            <!-- <h1>CONTACT</h1> -->
            <h2><?php echo $title; ?></h2>
            <div class="column fleft">
                <h3><?php echo $subtitle; ?></h3>
                <img src="<?php echo get_template_directory_uri(); ?>/img/profil.jpg" />
                <div id="texte">
                	<?php echo $content; ?>
                </div>
            </div>
            <div class="column fright">
                <h3>Contact me</h3>
                <form id="infoForm" action="<?php echo get_template_directory_uri(); ?>/mail.php" method="post">
                    <div class="input">
                        <input type="text" name="nom" id="nom" value="Nom" />
                        <input type="text" name="email" id="email" value="Email" />
                        <input type="text" name="objet" id="objet"  value="Objet" />
                        <textarea name="textarea" name="message" rows="10" cols="50">Message</textarea>
                    </div>
                    <br />
                    <input type="submit" value="ENVOYER" id="submit" class="column"/>
                </form>
            </div>
        </div>
    </div>
    <div id="contact-mobile">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut quis ligula vel turpis ornare rutrum. Donec mi ex, condimentum et lorem id, malesuada ullamcorper leo. Phasellus fringilla tempus maximus. Nulla semper dapibus ipsum sagittis egestas. Fusce efficitur velit id consectetur molestie. Sed quis nibh ultricies, imperdiet orci non, accumsan eros. Ut consectetur fringilla libero at mattis. Aenean ut justo vel lacus porta dictum molestie vitae nisi. In hac habitasse platea dictumst. Donec et felis sit amet purus vulputate porta nec quis nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
    </div>

<?php get_footer(); ?>
