<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class Count extends ISql
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
        return count($row_array);
    }
}