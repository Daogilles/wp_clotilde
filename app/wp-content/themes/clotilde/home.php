<?php
\F\JSONRenderer::getInstance()->pageType = 'home';
get_header(); ?>
<div class="">
    <div id="home" class="full-vertical">
        <h1>Clotilde Sourisseau</h1>
        <h2>Make up artist Paris</h2>
        <a href="#" class="arrow"></a>
    </div>

    <div class="beauty category">
        <div id="beauty" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fright">
                <h1>Beauty</h1>
                <a href="/beauty"> See gallery </a>
            </div>
        </div>
    </div>

    <div class="fashion category">
        <div id="fashion" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fleft">
                <h1>Fashion</h1>
                <a href="/fashion"> See gallery </a>
            </div>
        </div>
    </div>

    <div class="celebrities category">
        <div id="celebrities" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fright">
                <h1>Celebrities</h1>
                <a href="/celebrities"> See gallery </a>
            </div>
        </div>
    </div>

    <div class="bodyart category">
        <div id="bodyart" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fleft">
                <h1>Body-art</h1>
                <a href="/body-art"> See gallery </a>
            </div>
        </div>
    </div>

    <div class="services category">
        <div id="services" class="wrapper">
            <div class="category-content-bg"></div>
            <div class="category-content-bg-visible"></div>
            <div class="fright">
                <h1>Services</h1>
                <a href="/services"> See gallery </a>
            </div>
        </div>
    </div>

    
</div>

<?php get_footer(); ?>
