<?php
$URL_SCRIPT = get_template_directory_uri().'/scripts/';
$URL_STYLE = get_template_directory_uri().'/styles/';

//add all script and style

//default
\F\AssetManager::getInstance()->addStyle(\F\AssetManager::DEFAULT_GROUP_KEY, $URL_STYLE.'main.css');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_HEADER, $URL_SCRIPT.'vendor/Modernizr-2.8.2.js');

//vendor
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'vendor/lodash.underscore.min.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'vendor/backbone.min.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'vendor/greensock/plugins/CSSPlugin.min.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'vendor/greensock/easing/EasePack.min.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'vendor/greensock/TweenMax.min.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'vendor/slick.min.js');


//classes
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/Router.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/NavigationController.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/abstract/APageView.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/abstract/APageModel.js');


//different view page type
//\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/models/DefaultPage.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'eventManager.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'layoutManager.js');

//different model page type
//\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/views/DefaultPage.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/views/Home.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/views/Gallery.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/views/Services.js');
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'classes/views/Contact.js');


//script
\F\AssetManager::getInstance()->addScript(\F\AssetManager::DEFAULT_GROUP_KEY_FOOTER, $URL_SCRIPT.'main.js');

/*
\F\AssetManager::getInstance()->addScript('home', $URL_SCRIPT.'home.js', true);
\F\AssetManager::getInstance()->needScriptGroup(array('main'));
*/

