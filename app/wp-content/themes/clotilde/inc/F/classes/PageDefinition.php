<?php

namespace F;

class PageDefinition {

    public $slug;
    public $path;
    public $class;
    public $htmlFile;
    public $route;
    public $displayName;

    public function __construct($slug, $path, $route = null, $class = null, $htmlFile = null, $displayName = null) {
        $this->slug = $slug;
        $this->path = $path;
        $this->class = $class;
        $this->htmlFile = $htmlFile;
        $this->route = $route;
        $this->displayName = $displayName;
    }

    public function isClass() {
        return strlen($this->class) > 0;
    }

    public function isHTMLFile() {
        return strlen($this->htmlFile) > 0;
    }

}

?>
