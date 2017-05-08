<?php

namespace PhpCsv\Query\Execute;

class Entity
{
    private $row;

    public function setRow($row){
        $this->row = $row;
    }

    public function getRow(){
        return $this->row;
    }
}