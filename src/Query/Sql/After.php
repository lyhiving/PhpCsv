<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class After extends ISql
{
    private $id;

    /**
     * 値を設定する
     * @param ...$args
     */
    public function set(...$args){
        $this->id = $args[0];
    }

    /**
     * 実行の際にやること
     * @param $row_array
     * @return array
     */
    public function execute(&$row_array){

        $tmp = array();
        $tmp[] = end($row_array);

        return $tmp;
    }
}