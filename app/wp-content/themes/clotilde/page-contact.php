<?php
/*
Template Name: Contact
*/

get_header();
\F\JSONRenderer::getInstance()->pageType = 'contact';

$pagename = get_query_var('pagename');

$title = get_field('titre');
$subtitle = get_field('subtitle');
$content = get_field('texte');

?>
	<div id="contact">
        <div id="contact-wrapper" class="row">
            <!-- <h1>CONTACT</h1> -->
            <!-- <h2><?php echo $title; ?></h2> -->
            <div class="col-5 column contact-form">
                <div class="column-wrapper">
                    <h3>Contact me</h3>
                    <form id="infoForm" action="<?php echo get_template_directory_uri(); ?>/mail.php" method="post">
                        <div class="input">
                            <input type="text" name="nom" id="nom" value="Name" />
                            <input type="text" name="email" id="email" value="Email" />
                            <input type="text" name="objet" id="objet"  value="Object" />
                            <textarea name="textarea" name="message" rows="10" cols="50">Message</textarea>
                        </div>
                        <br />
                        <input type="submit" value="SEND" id="submit" />
                    </form>
                </div>
                
            </div>
            <div class="col-7 column contact-infos">
                <div class="column-wrapper">
                    <h3><?php echo $subtitle; ?></h3>
                    <img src="<?php echo get_template_directory_uri(); ?>/img/profil.jpg" />
                    <h3><?php echo $title; ?></h3>
                    <div id="texte">
                        <?php echo $content; ?>
                    </div>
                    <ul class="social">
                        <li><a href="https://www.facebook.com/Clotilde-Sourisseau-Makeup-Artist-455190067922898" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/facebook.png" alt=""></a></li>
                        <li><a href="https://instagram.com/clotildemakeupartist/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/instagram.png" alt=""></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        

<?php get_footer(); ?>
