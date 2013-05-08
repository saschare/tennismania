<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Db extends Aitsu_Db {

    /**
     * Based on Aitsu_Db::filter()
     * Extended by variable $groups
     * 
     * @since 1.2.6-1
     * @see Aitsu_Db::filter();
     */
    public static function filter($baseQuery, $limit = null, $offset = null, $filters = null, $orders = null, $groups = null) {

        $limit = is_null($limit) || !is_numeric($limit) ? 100 : $limit;
        $offset = is_null($offset) || !is_numeric($offset) ? 0 : $offset;
        $filters = is_array($filters) ? $filters : array();
        $orders = is_array($orders) ? $orders : array();
        $groups = is_array($groups) ? $groups : array();

        $filterClause = array();
        $filterValues = array();
        for ($i = 0; $i < count($filters); $i++) {
            $filterClause[] = $filters[$i]->clause . ' :value' . $i;
            $filterValues[':value' . $i] = $filters[$i]->value;
        }
        $where = count($filterClause) == 0 ? '' : 'where ' . implode(' and ', $filterClause);

        $orderBy = count($orders) == 0 ? '' : 'order by ' . implode(', ', $orders);
        $groupBy = count($groups) == 0 ? '' : 'group by ' . implode(', ', $groups);

        $results = Moraso_Db::fetchAll('' .
                        $baseQuery .
                        ' ' . $where .
                        ' ' . $groupBy .
                        ' ' . $orderBy .
                        'limit ' . $offset . ', ' . $limit, $filterValues);

        $return = array();

        if ($results) {
            foreach ($results as $result) {
                $return[] = (object) $result;
            }
        }

        return $return;
    }

    /**
     * @since 1.14.0-1
     */
    public static function delete($from, array $where) {

        $whereClause = array();
        $whereValues = array();

        foreach ($where as $key => $value) {
            $clause = '=';

            if (is_array($value)) {
                $clause = $value['clause'];
                $value = $value['value'];
            }

            $whereClause[] = $key . ' ' . $clause . ':value_' . $key;
            $whereValues[':value_' . $key] = $value;
        }

        Moraso_Db::query('delete from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
    }

    /**
     * @since 1.14.0-1
     */
    public static function simpleFetch($select, $from, array $where, $limit = 1, $caching = 0) {

        $whereClause = array();
        $whereValues = array();

        foreach ($where as $key => $value) {
            $clause = '=';

            if (is_array($value)) {
                $clause = $value['clause'];
                $value = $value['value'];
            }

            $whereClause[] = $key . ' ' . $clause . ':value_' . $key;
            $whereValues[':value_' . $key] = $value;
        }

        if ($caching === 0) {
            if ($limit === 1) {
                if (is_array($select)) {
                    return Moraso_Db::fetchRow('select ' . implode(', ', $select) . ' from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
                }

                return Moraso_Db::fetchOne('select ' . $select . ' from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
            } else {
                if (!is_array($select)) {
                    $select = str_split($select);
                }

                return Moraso_Db::fetchAll('select ' . implode(', ', $select) . ' from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
            }
        } else {
            if ($limit === 1) {
                if (is_array($select)) {
                    return Moraso_Db::fetchRowC($caching, 'select ' . implode(', ', $select) . ' from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
                }

                return Moraso_Db::fetchOneC($caching, 'select ' . $select . ' from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
            } else {
                if (!is_array($select)) {
                    $select = str_split($select);
                }

                return Moraso_Db::fetchAllC($caching, 'select ' . implode(', ', $select) . ' from ' . $from . ' where ' . implode(' and ', $whereClause) . '', $whereValues);
            }
        }
    }

}