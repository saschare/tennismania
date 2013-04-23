<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Transformation_Css implements Aitsu_Event_Listener_Interface {

    public static function notify(Aitsu_Event_Abstract $event) {

        if (!isset($event->bootstrap->pageContent)) {
            return;
        }

        $css_refs = Moraso_Util_Css::getReferences();
        if (!empty($css_refs)) {
            $refs = '';
            foreach ($css_refs as $ref) {
                $refs .= '<link rel="stylesheet" type="text/css" href="' . $ref . '" />' . "\n";
            }
            $event->bootstrap->pageContent = str_replace('</head>', "{$refs}</head>", $event->bootstrap->pageContent);
        }

        $css = Moraso_Util_Css::get();
        if (!empty($css)) {
            $css = '<style type="text/css">' . $css . '</style>';
            $event->bootstrap->pageContent = str_replace('</head>', "{$css}</head>", $event->bootstrap->pageContent);
        }
    }

}