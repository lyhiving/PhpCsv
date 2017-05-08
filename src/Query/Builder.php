<?php

namespace PhpCsv\Query;

use PhpCsv\Query\Sql\Count;
use PhpCsv\Query\Sql\Select;
use PhpCsv\Query\Sql\Update;
use PhpCsv\Query\Sql\Where;
use PhpCsv\Query\Sql\First;

class Builder
{
    const ACTION_TYPE_SELECT = 1;
    const ACTION_TYPE_INSERT = 2;
    const ACTION_TYPE_UPDATE = 3;
    const ACTION_TYPE_DELETE = 4;


    private $model = null;

    private $action = -1;
    private $sql_queue;

    /**
     * constructor
     * @param $model
     */
    public function __construct($model){
        $this->model = $model;
        $this->sql_queue = new SqlQueue();
    }

    /**
     * insert statement
     * @return $this
     */
    public function select(){
        $this->action = self::ACTION_TYPE_SELECT;

        $sql = new Select();
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * insert statement
     * @param $options
     * @return $this
     */
    public function insert($options){
        $this->action = self::ACTION_TYPE_INSERT;

        $sql = new Insert();
        $sql->set($options);
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * update statement
     * @param $options
     * @return $this
     */
    public function update($options){
        $this->action = self::ACTION_TYPE_UPDATE;

        $sql = new Update();
        $sql->set($options);
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * delete statement
     * @return $this
     */
    public function delete(){
        $this->action = self::ACTION_TYPE_DELETE;

        $sql = new Delete();
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * sql入力終了
     * @return mixed
     */
    public function &done(){
        $row_array = $this->model->get();

        $executor = new Executor();
        $result = $executor->execute($row_array, $this->action, $this->sql_queue);

        if($this->action == self::ACTION_TYPE_INSERT){
            $this->model->save();
        }else if($this->action == self::ACTION_TYPE_UPDATE){
            $this->model->save();
        }else if($this->action == self::ACTION_TYPE_DELETE){
            $this->model->save();
        }

        return $result;
    }

    /**
     * limit statement
     * @param $count
     * @return $this
     */
    public function limit($count){
        $sql = new Limit();
        $sql->set($count);
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * 最先頭のデータを取得する、配列構成ではない
     * @return $this
     */
    public function first(){
        $sql = new First();
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * 最後のデータを取得する、配列構成ではない
     * @return $this
     */
    public function last(){
        $sql = new Last();
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * count statement
     * @return $this
     */
    public function count(){
        $sql = new Count();
        $this->sql_queue->enPostQueue($sql);

        return $this;
    }

    /**
     * where statement
     * @param $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null){

        $sql = new Where();
        $sql->set($column, $operator, $value);
        $this->sql_queue->enQueue($sql);

        return $this;
    }
}