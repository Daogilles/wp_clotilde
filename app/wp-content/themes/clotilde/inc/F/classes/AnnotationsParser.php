<?php

namespace F{

    /**
     * Annotation parser class, allows you to use php annotations in the code
     *
     * @author Jeremy P
     */
    class AnnotationsParser {

        /**
         * Reflector
         * @var \Reflector
         */
        private $_reflector = null;
        /**
         * Reflector Properties
         * @var array
         */
        private $_properties = array();
        /**
         * Array with every line of the file
         * @var array
         */
        private $_code = array();
        /**
         * Cached array of annotations
         * @var type
         */
        private $_cached = array();

        /**
         * Constructor
         * @param \Reflector $reflector
         * @return AnnotationsParser
         * @throws \Error
         */
        public function __construct($reflector) {

            if (!$reflector instanceof \Reflector) {
                  Error::throwException("Only Reflector object can be parsed");
                  return;
            }

            $this->_reflector = $reflector;
            $this->_properties = $this->_reflector->getProperties();
            if(file_exists($this->_reflector->getFileName())) {
                $this->_code = file($this->_reflector->getFileName());
                //trace($contenu_array);
            } else {
                Error::throwException("File ".$this->_reflector->getFileName()." not found");
                return;
            }
            return $this;
        }

        /**
         * Parse a file to find annotations $name
         *
         * Example:
         * <code>
         * //Get Class attributes annotations
         * $c = new \ReflectionClass(get_called_class());
         * $annotation = new \F\AnnotationsParser($c);
         * $dbfields = $annotation->parse('dbFieldInfo');
         * </code>
         *
         * @param string $name
         * @return array
         */
        public function parse($name) {
            if(!empty($this->_cached[$name])) return $this->_cached[$name];
            //trace($this->_reflector->__toString());

            $tabs = array();
            $lineFound = null;
            foreach($this->_code as $line) {

                if($lineFound !== null) {
                    if(preg_match('/public |private |protected /i', $line) !== 0) {
                        $property = preg_replace('/public |private |protected | |\n|\$|;/i', '', $line);
                        $values = preg_replace('/\/|\*| |\n|\$|;|@'.$name.'/i', '', $lineFound);
                        $tValues = explode(',', $values);
                        $finalValues = array();
                        foreach($tValues as $v) {
                            $v = explode('=', $v);
                            if(count($v) > 0) {
                                $v[1] = $v[1] == 'true' ? true : ($v[1] == 'false' ? false  : $v[1]);
                                $finalValues[$v[0]] = trim($v[1]);
                            }
                        }
                        $tmps = explode('=', $property);
                        $tabs[trim($tmps[0])] = $finalValues;
                        $lineFound = null;
                    }
                }
                if(strpos($line, '@'.$name.' ') !== false) {
                    $lineFound = $line;
                }
            }

            $this->_cached[$name] = $tabs;
            return $tabs;
        }
    }

}
