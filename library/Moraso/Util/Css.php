<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Util_Css {

    protected $css = array();
    protected $reference = array();

    protected function __construct() {
        
    }

    protected static function _getInstance() {

        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public static function add($css) {

        self::_getInstance()->css[] = $css;
    }

    public static function addReference($css) {

        self::_getInstance()->reference[] = $css;
    }

    public static function get() {

        if (count(self::_getInstance()->css) == 0) {
            return self::_getInstance()->css;
        }

        $css = array_unique(self::_getInstance()->css);

        return implode('', $css);
    }

    public static function getReferences() {

        if (count(self::_getInstance()->reference) == 0) {
            return self::_getInstance()->reference;
        }

        $reference = array_unique(self::_getInstance()->reference);

        return $reference;
    }

}