<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Google_Maps_Class extends Moraso_Module_Abstract {

    protected function _getDefaults() {

        $defaults = array(
            'name' => 'webtischlerei',
            'address' => 'Von-Kronenfeldt-Str. 20, 27318 Hoya, Deutschland',
            'maxZoom' => 0,
            'minZoom' => 0,
            'zoom' => 17,
            'zoomControl' => true,
            'type' => 'HYBRID',
            'control' => true,
            'controlPosition' => 'TOP_LEFT',
            'controlStyle' => 'DEFAULT',
            'configurable' => array(
                'name' => true,
                'address' => true,
                'maxZoom' => true,
                'minZoom' => true,
                'zoom' => true,
                'zoomControl' => true,
                'type' => true,
                'control' => true,
                'controlPosition' => true,
                'mapTypeControlStyle' => true
            )
        );

        return $defaults;
    }

    protected function _init() {

        Aitsu_Util_Javascript::addReference('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=de');
    }

    protected function _main() {

        $defaults = $this->_moduleConfigDefaults;

        $translation = array();
        $translation['configuration'] = Aitsu_Translate::_('Configuration');
        $translation['zoom'] = Aitsu_Translate::_('Zoom');
        $translation['control'] = Aitsu_Translate::_('Control');

        /* Name */
        if ($defaults['configurable']['name']) {
            $name = Aitsu_Content_Config_Text::set($this->_index, 'name', Aitsu_Translate::translate('Name'), $translation['configuration']);
        }

        $name = !empty($name) ? $name : $defaults['name'];

        /* Address */
        if ($defaults['configurable']['address']) {
            $address = Aitsu_Content_Config_Text::set($this->_index, 'address', Aitsu_Translate::translate('Address'), $translation['configuration']);
        }

        $address = !empty($address) ? $address : $defaults['address'];

        /* max. Zoom */
        if ($defaults['configurable']['maxZoom']) {
            $maxZoom = Aitsu_Content_Config_Text::set($this->_index, 'maxZoom', Aitsu_Translate::_('max. Zoom'), $translation['zoom']);
        }

        $maxZoom = !empty($maxZoom) ? (int) $maxZoom : $defaults['maxZoom'];

        /* min. Zoom */
        if ($defaults['configurable']['minZoom']) {
            $minZoom = Aitsu_Content_Config_Text::set($this->_index, 'minZoom', Aitsu_Translate::_('min. Zoom'), $translation['zoom']);
        }

        $minZoom = !empty($minZoom) ? (int) $minZoom : $defaults['minZoom'];

        /* Zoom */
        if ($defaults['configurable']['zoom']) {
            $zoom = Aitsu_Content_Config_Text::set($this->_index, 'zoom', Aitsu_Translate::_('Zoom'), $translation['zoom']);
        }

        $zoom = !empty($zoom) ? (int) $zoom : $defaults['zoom'];

        /* zoomControl */
        if ($defaults['configurable']['zoomControl']) {
            $zoomControlSelect = array(
                'default' => '',
                'true' => 1,
                'false' => 0
            );

            $zoomControl = Aitsu_Content_Config_Select::set($this->_index, 'zoomControl', Aitsu_Translate::_('Zoom Control'), $zoomControlSelect, $translation['zoom']);
        }

        $zoomControl = isset($zoomControl) && strlen($zoomControl) > 0 ? filter_var($zoomControl, FILTER_VALIDATE_BOOLEAN) : $defaults['zoomControl'];

        /* Type */
        if ($defaults['configurable']['type']) {
            $typeSelect = array(
                'default' => '',
                'This map type displays a transparent layer of major streets on satellite images.' => 'HYBRID',
                'This map type displays a normal street map.' => 'ROADMAP',
                'This map type displays satellite images.' => 'SATELLITE',
                'This map type displays maps with physical features such as terrain and vegetation.' => 'TERRAIN'
            );

            $type = Aitsu_Content_Config_Select::set($this->_index, 'type', Aitsu_Translate::_('Type'), $typeSelect, $translation['configuration']);
        }

        $type = !empty($type) ? $type : $defaults['type'];

        /* control */
        if ($defaults['configurable']['control']) {
            $controlSelect = array(
                'default' => '',
                'true' => 1,
                'false' => 0
            );

            $control = Aitsu_Content_Config_Select::set($this->_index, 'control', Aitsu_Translate::_('Control'), $controlSelect, $translation['control']);
        }

        $control = isset($control) && strlen($control) > 0 ? filter_var($control, FILTER_VALIDATE_BOOLEAN) : $defaults['control'];

        /* controlPosition */
        if ($defaults['configurable']['controlPosition']) {
            $controlPositionSelect = array(
                'default' => '',
                'Elements are positioned in the center of the bottom row' => 'BOTTOM_CENTER',
                'Elements are positioned in the bottom left and flow towards the middle. Elements are positioned to the right of the Google logo.' => 'BOTTOM_LEFT',
                'Elements are positioned in the bottom right and flow towards the middle. Elements are positioned to the left of the copyrights.' => 'BOTTOM_RIGHT',
                'Elements are positioned on the left, above bottom-left elements, and flow upwards.' => 'LEFT_BOTTOM',
                'Elements are positioned in the center of the left side.' => 'LEFT_CENTER',
                'Elements are positioned on the left, below top-left elements, and flow downwards.' => 'LEFT_TOP',
                'Elements are positioned on the right, above bottom-right elements, and flow upwards.' => 'RIGHT_BOTTOM',
                'Elements are positioned in the center of the right side.' => 'RIGHT_CENTER',
                'Elements are positioned on the right, below top-right elements, and flow downwards.' => 'RIGHT_TOP',
                'Elements are positioned in the center of the top row.' => 'TOP_CENTER',
                'Elements are positioned in the top left and flow towards the middle.' => 'TOP_LEFT',
                'Elements are positioned in the top right and flow towards the middle.' => 'TOP_RIGHT'
            );

            $controlPosition = Aitsu_Content_Config_Select::set($this->_index, 'controlPosition', Aitsu_Translate::_('Control Position'), $controlPositionSelect, $translation['control']);
        }

        $controlPosition = !empty($controlPosition) ? $controlPosition : $defaults['controlPosition'];

        /* controlStyle */
        if ($defaults['configurable']['controlStyle']) {
            $controlStyleSelect = array(
                'Uses the default map type control. The control which DEFAULT maps to will vary according to window size and other factors. It may change in future versions of the API.' => 'DEFAULT',
                'A dropdown menu for the screen realestate conscious.' => 'DROPDOWN_MENU',
                'The standard horizontal radio buttons bar.' => 'HORIZONTAL_BAR',
            );

            $controlStyle = Aitsu_Content_Config_Select::set($this->_index, 'controlStyle', Aitsu_Translate::_('Control Style'), $controlStyleSelect, $translation['control']);
        }

        $controlStyle = !empty($controlStyle) ? $controlStyle : $defaults['controlStyle'];

        $view = $this->_getView();

        $view->index = $this->_index;
        $view->name = $name;
        $view->address = $address;
        $view->maxZoom = $maxZoom;
        $view->minZoom = $minZoom;
        $view->zoom = $zoom;
        $view->zoomControl = $zoomControl ? 'true' : 'false';
        $view->type = $type;
        $view->control = $control ? 'true' : 'false';
        $view->controlPosition = $controlPosition;
        $view->controlStyle = $controlStyle;

        Aitsu_Util_Javascript::add($view->render('js.phtml'));

        return $view->render('index.phtml');
    }

}