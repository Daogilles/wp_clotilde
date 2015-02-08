<?php

namespace F {

    /**
     * AttributeMutualizer class
     * This class is dedicated to performance.
     * Allows you to mutualize class attributes in a singleton for performance reasons.
     * This is useful to store computed class attributes (such as db fields)
     */
    class AttributeMutualizer {

        /**
         * Instance
         * @var AttributeMutualizer
         */
        private static $_instance;

        /**
         * The variables to mutualize
         * @var array
         */
        private $_mutualized;


        /**
         * Constructor
         */
        public function __construct() { }

        /**
         * Prevent external instance cloning
         */
        private function __clone () {}


        /**
         * Get instance and init if empty
         *
         * Example
         * <code>
         * $mz = AttributeMutualizer::getInstance();
         * </code>
         *
         * @return DB
         */
        public static function getInstance () {
            if (!(self::$_instance instanceof self)){
                $instance = new self();
                self::$_instance = $instance;
            }

            return self::$_instance;
        }

        /**
         * Set the mutualized attribute for a class
         *
         * Example:
         * <code>
         * $mz = AttributeMutualizer::getInstance();
         * $mz->setMutualized($calledClass,'dbfields',$dbfields);
         * </code>
         *
         * @see \NOE\SIT\AModel::getFields();
         * @param string $class
         * @param attribute $attribute
         * @param type $value
         */
        public function setMutualized($class,$attribute,$value){
            $mutualized = $this->_mutualized;
            $mutualized[$class][$attribute] = $value;
            $this->_mutualized = $mutualized;
        }

        /**
         * Get the mutualized value
         *
         * Example:
         * <code>
         * $mz = AttributeMutualizer::getInstance();
         * $dbfields = $mz->getMutualized($calledClass,'dbfields');
         * </code>
         *
         * @see \NOE\SIT\AModel::getFields();
         * @param string $class
         * @param string $attribute
         * @return type
         */
        public function getMutualized($class,$attribute){
            $mutualized = $this->_mutualized;
            return !empty($mutualized[$class][$attribute]) ? $mutualized[$class][$attribute] : null;
        }

    }

}