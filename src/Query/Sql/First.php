<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class First extends ISql
{

    /**
     * 値を設定する
     * @param ...$args
     */
    public function set(...$args){

    }

    /**
     * 実行の際にやること
     * @param $row_array
     * @return array
     */
    public function execute(&$row_array){
        return reset($row_array);
    }
}