<?php
/**
 * Created by PhpStorm.
 * User: Gwen
 * Date: 07/07/14
 * Time: 16:15
 */

namespace F;


class JSONRenderer {

    public $id;
    public $content;
    public $title;
    public $seoTitle;
    public $seoDesc;
    public $bodyClass = array();
    public $pageType;
    public $data = array();

    private static $_instance;

    public static function getInstance() {
        if(!self::$_instance instanceof self) {
            self::$_instance = new JSONRenderer();
        }
        return self::$_instance;
    }

    public function jsonify() {
        return array(
            'id' => $this->id,
            'content' => $this->content,
            'title' => $this->title,
            'seoTitle' => $this->seoTitle,
            'seoDesc' => $this->seoDesc,
            'bodyClass' => join(' ', $this->bodyClass),
            'pageType' => $this->pageType,
            'data' => $this->data
        );
    }

} 