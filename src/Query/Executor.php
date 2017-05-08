<?php

namespace PhpCsv\Query;


class Executor
{
    public function __construct(){

    }


    public function execute(&$row_array, $action, $sql_queue){
        if($action == Builder::ACTION_TYPE_SELECT){
            return $this->executeSelect($row_array, $sql_queue);
        }else if($action == Builder::ACTION_TYPE_INSERT){
            return $this->executeInsert($row_array, $sql_queue);
        }else if($action == Builder::ACTION_TYPE_UPDATE){
            return $this->executeUpdate($row_array, $sql_queue);
        }else if($action == Builder::ACTION_TYPE_DELETE){
            return $this->executeDelete($row_array, $sql_queue);
        }
    }

    /**
     * Selectのためにの処理
     * @param $row_array
     * @param $sql_queue
     * @return mixed
     */
    private function &executeSelect(&$row_array, $sql_queue){
        $select_row = $this->executeSqlQueue($row_array, $sql_queue);

        return $select_row;
    }

    /**
     * Insertのためにの処理
     * @param $row_array
     * @param $sql_queue
     * @return mixed
     */
    private function executeInsert($row_array, SqlQueue $sql_queue){
        $ret = $this->executeSqlQueue($row_array, $sql_queue);

        if(is_array($ret)){
            $anchor_row = end($ret);
        }else{
            $anchor_row = $ret;
        }

        array_splice($row_array, $anchor_row->getLineNo(), 0, $sql_queue->getEntity()->getRow());

        return $row_array;
    }

    /**
     * Updateのためにの処理
     * @param $row_array
     * @param $sql_queue
     * @return mixed
     */
    private function executeUpdate($row_array, $sql_queue){
        $update_row = $this->executeSqlQueue($row_array, $sql_queue);

        foreach($update_row as $row){
            $line_no = $row->getLineNo();

            $row_array[$line_no] = $row;
        }

        return $row_array;
    }

    /**
     * Deleteのためにの処理
     * @param $row_array
     * @param $sql_queue
     * @return mixed
     */
    private function executeDelete($row_array, $sql_queue){
        $delete_row = $this->executeSqlQueue($row_array, $sql_queue);

        foreach($delete_row as $row){
            $line_no = $row->getLineNo();

            unset($row_array[$line_no]);
        }

        return $row_array;
    }

    /**
     * Sql Queueを実行する
     * @param $row_array
     * @param $sql_queue
     * @return mixed
     */
    private function &executeSqlQueue(&$row_array, $sql_queue){
        $sql = $sql_queue->deAllQueue();

        while($sql !== null){
            $row_array = $sql->execute($row_array);

            $sql = $sql_queue->deAllQueue();
        }

        return $row_array;
    }

    /**
     * Lineとインデックスを整形
     * @param $row_array
     * @return array
     */
    private function updateLineNo($row_array){
        $tmp = array();

        $line_no = 1;

        foreach($row_array as $row){
            $row->setLineNo($line_no);

            $tmp[$line_no] = $line_no;
        }

        return $tmp;
    }
}