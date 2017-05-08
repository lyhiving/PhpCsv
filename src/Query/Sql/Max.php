<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class Max extends ISql
{
    private $column;

    /**
     * 値を設定する
     * @param ...$args
     */
    public function set(...$args){
        $this->column = $args[0];
    }

    /**
     * 実行の際にやること
     * @param $row_array
     * @return array
     */
    public function execute(&$row_array){
        $max_row = $row_array[0];
        $max_val = $max_row[$this->column];

        foreach($row_array as $row){
            if($row[$this->column] > $max_val){

                $max_row = $row;
                $max_val = $row[$this->column];

            }
        }

        return $max_row;
    }
}