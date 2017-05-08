<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class Limit extends ISql
{
    private $limit;

    /**
     * 値を設定する
     * @param ...$args
     */
    public function set(...$args){
        $this->limit = $args[0];
    }

    /**
     * 実行の際にやること
     * @param $row_array
     * @return array
     */
    public function execute(&$row_array){
        $tmp = array();

        foreach($row_array as $row){

            if(!$row->isComment()) {

                if(count($tmp) < $this->limit){
                    $tmp[] = $row;
                }
            }
        }

        return $tmp;
    }
}