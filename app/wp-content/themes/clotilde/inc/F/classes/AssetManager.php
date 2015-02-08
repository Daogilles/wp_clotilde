<?php

namespace F;

class AssetManager {

    //config
    const DEFAULT_GROUP_KEY = 'default';
    const DEFAULT_GROUP_KEY_HEADER = 'default-header';
    const DEFAULT_GROUP_KEY_FOOTER = 'default-footer';

    //singleton
    private static $_instance = null;
    
    public static function getInstance() {
        if(!self::$_instance instanceof self) {
            self::$_instance = new AssetManager();
        }
        return self::$_instance;
    }

    //properties
    public $STYLES_GROUP = array(AssetManager::DEFAULT_GROUP_KEY => array());
    public $SCRIPTS_GROUP = array(AssetManager::DEFAULT_GROUP_KEY_HEADER => array(), AssetManager::DEFAULT_GROUP_KEY_FOOTER => array());

    private $_REQUESTED_SCRIPTS = array('header' => array(AssetManager::DEFAULT_GROUP_KEY_HEADER), 'footer' => array(AssetManager::DEFAULT_GROUP_KEY_FOOTER));
    private $_REQUESTED_STYLES = array(AssetManager::DEFAULT_GROUP_KEY);


    //methods
    public function addScript($group, $url, $canMin = true) {
        if(empty($this->SCRIPTS_GROUP[$group])) {
            $this->SCRIPTS_GROUP[$group] = array();
        }
        $this->SCRIPTS_GROUP[$group][] = array('src' => $url, 'canMin' => $canMin);
    }

    public function addStyle($group, $url, $canMin = true) {
        if(empty($this->STYLES_GROUP[$group])) {
            $this->STYLES_GROUP[$group] = array();
        }
        $this->STYLES_GROUP[$group][] = array('src' => $url, 'canMin' => $canMin);
    }

    public function needScriptGroup($groupsName, $inFooter = true) {
        $position = $inFooter ? 'footer' : 'header';
        if(!is_array($groupsName)) $groupsName = array($groupsName);
        foreach($groupsName as $groupName) {
            if(!in_array($groupName, $this->_REQUESTED_SCRIPTS[$position])) {
                $this->_REQUESTED_SCRIPTS[$position][] = $groupName;
            }
        }
    }

    public function needStyleGroup($groupsName) {
        if(!is_array($groupsName)) $groupsName = array($groupsName);
        foreach($groupsName as $groupName) {
            if(!in_array($groupName, $this->_REQUESTED_STYLES)) {
                $this->_REQUESTED_STYLES[] = $groupName;
            }
        }
    }

    private function _getScriptGroup($name) {
        return !empty($this->SCRIPTS_GROUP[$name]) ? $this->SCRIPTS_GROUP[$name] : array();
    }

    private function _getStyleGroup($name) {
        return !empty($this->STYLES_GROUP[$name]) ? $this->STYLES_GROUP[$name] : array();
    }

    public function getScript($header = true) {
       $scripts = array();
       $position = $header ? 'header' : 'footer';
       if(!empty($this->_REQUESTED_SCRIPTS[$position])) {
           foreach($this->_REQUESTED_SCRIPTS[$position] as $group) {
                $scripts[$group] = $this->_getScriptGroup($group);
           }
       }
        return $scripts;
    }

    public function getStyles() {
        $styles = array();
        if(!empty($this->_REQUESTED_STYLES )) {
            foreach($this->_REQUESTED_STYLES as $group) {
                $styles[$group] = $this->_getStyleGroup($group);
            }
        }
        return $styles;
    }
} 