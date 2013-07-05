<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Comments
{
    public static function create($parent_node_id, $comment, $public = true, $active = false)
    {
        /* Node erstellen */
        $comment['node_id'] = Moraso_Nodes::insert($parent_node_id, $public, $active);

        /* Kommentar erstellen */
        return Moraso_Db::put('_nodes_comments', null, $comment);
    }

    public static function getComments($node_id, $startLevel = 1, $maxLevel = 2)
    {
        $comments = Moraso_Nodes::getNodes($node_id, $startLevel);

        foreach ($comments as &$comment) {            
            $comment['comment'] = Moraso_Db::fetchRow('' .
                            'SELECT ' .
                            '   verfasser, ' .
                            '   nachricht ' .
                            'FROM ' .
                            '   _nodes_comments ' .
                            'WHERE ' .
                            '   node_id =:node_id', array(
                        ':node_id' => $comment['node_id']
            ));

            if ($comment['has_children'] && $startLevel < $maxLevel) {
                $comment['sub_comments'] = self::getComments($comment['node_id'], $startLevel + 1, $maxLevel);
            }
        }

        return $comments;
    }

}