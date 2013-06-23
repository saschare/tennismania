<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Eav {

    public static function delete($id) {

        Moraso_Db::delete('_eav_entity', array('entity_id' => $id));
    }

    public static function set($attribute_set, $data) {

        $id = isset($data['id']) && !empty($data['id']) ? $data['id'] : Moraso_Db::query('insert into _eav_entity (entity_id) values (NULL)')->getLastInsertId();

        unset($data['id']);

        foreach ($data as $attribute_alias => $value) {

            $attribute_set_id = self::_getAttributeSetId($attribute_set);

            if (!$attribute_set_id) {
                $attribute_set_id = Moraso_Db::put('_eav_attribute_set', 'attribute_set_id', array(
                            'attribute_set_name' => $attribute_set
                ));
            }

            $attribute_id = Moraso_Db::simpleFetch('attribute_id', '_eav_attribute', array(
                        'attribute_alias' => $attribute_alias,
                        'attribute_set_id' => $attribute_set_id
            ));

            if (!$attribute_id) {
                $attribute_id = Moraso_Db::put('_eav_attribute', 'attribute_id', array(
                            'attribute_alias' => $attribute_alias,
                            'attribute_set_id' => $attribute_set_id
                ));
            }

            if (is_float($value)) {
                $type = 'float';
            } elseif (is_numeric($value)) {
                $type = 'integer';
            } else {
                $type = 'string';
            }

            $entity_attribute_id = Moraso_Db::simpleFetch('entity_attribute_id', '_eav_entity_attribute', array(
                        'entity_id' => $id,
                        'attribute_id' => $attribute_id
            ));

            if (!empty($entity_attribute_id)) {
                $value_id = Moraso_Db::simpleFetch('value_id', '_eav_value', array(
                            'entity_attribute_id' => $entity_attribute_id
                ));
            } else {
                $entity_attribute_id = Moraso_Db::put('_eav_entity_attribute', 'entity_attribute_id', array(
                            'entity_id' => $id,
                            'attribute_id' => $attribute_id
                ));

                $value_id = Moraso_Db::put('_eav_value', 'value_id', array(
                            'entity_attribute_id' => $entity_attribute_id
                ));
            }

            if (is_array($value) || is_object($value)) {
                $value = serialize($value);
            }

            $data = array(
                'value_id' => $value_id,
                'value_' . $type => $value,
                'entity_attribute_id' => $entity_attribute_id
            );

            Moraso_Db::delete('_eav_value', array('value_id' => $value_id));
            Moraso_Db::put('_eav_value', NULL, $data);
        }
    }

    public static function get($attribute_set, $entity_id = null) {

        $attribute_set_id = self::_getAttributeSetId($attribute_set);

        $data = array();

        if (!empty($entity_id)) {
            $rows = Moraso_Db::fetchAll('' .
                            'select ' .
                            '   a.attribute_alias, ' .
                            '   coalesce( ' .
                            '       v.value_string, ' .
                            '       v.value_integer, ' .
                            '       v.value_float ' .
                            '   ) as value ' .
                            'from ' .
                            '   _eav_entity as e ' .
                            'left join ' .
                            '   _eav_entity_attribute as ea on ea.entity_id = e.entity_id ' .
                            'left join ' .
                            '   _eav_attribute as a on a.attribute_id = ea.attribute_id ' .
                            'left join ' .
                            '   _eav_value as v on v.entity_attribute_id = ea.entity_attribute_id ' .
                            'where ' .
                            '   ea.entity_id =:entity_id ' .
                            'and ' .
                            '   a.attribute_set_id =:attribute_set_id', array(
                        ':attribute_set_id' => $attribute_set_id,
                        ':entity_id' => $entity_id
                            )
            );

            foreach ($rows as $row) {
                $data[$row['attribute_alias']] = $row['value'];
            }
        } else {
            $rows = Moraso_Db::fetchAll('' .
                            'select ' .
                            '   e.entity_id as id, ' .
                            '   a.attribute_alias, ' .
                            '   coalesce( ' .
                            '       v.value_string, ' .
                            '       v.value_integer, ' .
                            '       v.value_float ' .
                            '   ) as value ' .
                            'from ' .
                            '   _eav_entity as e ' .
                            'left join ' .
                            '   _eav_entity_attribute as ea on ea.entity_id = e.entity_id ' .
                            'left join ' .
                            '   _eav_attribute as a on a.attribute_id = ea.attribute_id ' .
                            'left join ' .
                            '   _eav_value as v on v.entity_attribute_id = ea.entity_attribute_id ' .
                            'where ' .
                            '   a.attribute_set_id =:attribute_set_id', array(
                        ':attribute_set_id' => $attribute_set_id
                            )
            );


            foreach ($rows as $row) {
                $data[$row['id']][$row['attribute_alias']] = $row['value'];
            }

            foreach ($rows as $row) {
                $data[$row['id']]['id'] = $row['id'];
            }

            sort($data);
        }

        return $data;
    }

    private static function _getAttributeSetId($set_name) {

        return Moraso_Db::simpleFetch('attribute_set_id', '_eav_attribute_set', array(
                    'attribute_set_name' => $set_name
        ));
    }

}