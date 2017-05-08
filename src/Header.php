<?php

namespace PhpCsv;


class Header extends Row
{
    protected  $reverse_data_array;

    public function __construct(Row $row){
        $this->data_array = $row->getDataArray();

        $this->reverse_data_array = array();
        foreach($this->data_array as $key => $data){
            $this->reverse_data_array[$data] = $key;
        }
    }

    public function getReverseDataArray(){
        return $this->reverse_data_array;
    }
}