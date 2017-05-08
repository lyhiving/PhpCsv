<?php

namespace PhpCsv\Factory;

use PhpCsv\Row;

class RowFactory
{
    public static function createRowFromExistOne(Row $row){

        $new_row = clone $row;

        $new_row->resetData();

        return $row;
    }

    public static function createRowFromHeader($header){

    }
}