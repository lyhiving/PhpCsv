<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class Min extends ISql
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
        $min_row = $row_array[0];
        $min_val = $min_row[$this->column];

        foreach($row_array as $row){
            if($row[$this->column] < $min_val){

                $min_row = $row;
                $min_val = $row[$this->column];

            }
        }

        return $min_row;
    }
}