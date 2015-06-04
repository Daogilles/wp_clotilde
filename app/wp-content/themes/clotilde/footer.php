<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Parislanuit
 */

if(isAjax()) {

    $json = \F\JSONRenderer::getInstance();
    $json->content = ob_get_contents();
    
    ob_end_clean();

    echo json_encode($json->jsonify());
    die();

} else { ?>
</div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>scripts/vendor/jquery-1.11.1.min.js"><\/script>')</script>
<?php
wp_footer();
\F\Renderer::getInstance()->showScripts();

?>

</body>
</html>
<?php } ?>
