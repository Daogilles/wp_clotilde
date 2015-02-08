<?php
\F\JSONRenderer::getInstance()->pageType = 'home';
get_header(); ?>
<div class="">
    <div id="home" class="full-vertical">
        <h1>Clotilde Sourisseau</h1>
        <h2>Make up artist Paris</h2>
    </div>

    <div class="beauty category">
        <div id="beauty" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fright">
                <h1>Beauty</h1>
                <a href="/beauty">>> Accéder à la galerie <<</a>
            </div>
        </div>
    </div>

    <div class="fashion category">
        <div id="fashion" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fleft">
                <h1>Fashion</h1>
                <a href="/fashion">>> Accéder à la galerie <<</a>
            </div>
        </div>
    </div>

    <div class="celebrities category">
        <div id="celebrities" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fright">
                <h1>Celebrities</h1>
                <a href="/celebrities">>> Accéder à la galerie <<</a>
            </div>
        </div>
    </div>

    <div class="bodyart category">
        <div id="bodyart" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fleft">
                <h1>Body-art</h1>
                <a href="/body-art">>> Accéder à la galerie <<</a>
            </div>
        </div>
    </div>

    <div class="services category">
        <div id="services" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fright">
                <h1>Services</h1>
                <a href="/contact">>> Accéder à la galerie <<</a>
            </div>
        </div>
    </div>

    
</div>

<?php get_footer(); ?>
