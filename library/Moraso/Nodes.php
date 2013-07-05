<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Nodes
{
    const table = '_nodes';
    const primary = 'node_id';

    public static function insert($parent_node_id, $public, $active)
    {
        return Moraso_NestedSets::insert($parent_node_id, $public, $active, self::table, self::primary);
    }
    
    public static function getNodes($parent_node_id, $level = 0)
    {
        return Moraso_NestedSets::getSet($parent_node_id, $level, self::table, self::primary);
    }

}