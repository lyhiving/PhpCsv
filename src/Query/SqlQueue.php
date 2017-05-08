<?php

namespace PhpCsv\Query;


use PhpCsv\Query\Execute\Entity;

class SqlQueue
{
    private $pre_queue = array();       //実行順番優先1のqueue
    private $queue = array();           //実行順番優先2のqueue
    private $post_queue = array();      //実行順番優先3のqueue

    private $entity;                    //Sql実行の時状態を記録

    /**
     * constructor
     * @param $model
     */
    public function __construct(){
        $this->entity = new Entity();
    }

    /**
     * Sqlオブジェクトを追加
     * @param $sql
     */
    public function enQueue($sql){
        $sql->setEntity($this->entity);

        $this->queue[] = $sql;
    }

    /**
     * SqlオブジェクトはPreキューへ追加
     * @param $sql
     */
    public function enPreQueue($sql){
        $sql->setEntity($this->entity);

        $this->pre_queue[] = $sql;
    }

    /**
     * SqlオブジェクトはPostキューへ追加
     * @param $sql
     */
    public function enPostQueue($sql){
        $sql->setEntity($this->entity);

        $this->post_queue[] = $sql;
    }

    /**
     * キューからSqlオブジェクトを取得
     * @return mixed|null
     */
    public function deQueue(){
        if(count($this->queue) == 0){
            return null;
        }

        return array_shift($this->queue);
    }

    /**
     * PreキューからSqlオブジェクトを取得
     * @return mixed|null
     */
    public function dePreQueue(){
        if(count($this->pre_queue) == 0){
            return null;
        }

        return array_shift($this->pre_queue);
    }

    /**
     * PostキューからSqlオブジェクトを取得
     * @return mixed|null
     */
    public function dePostQueue(){
        if(count($this->post_queue) == 0){
            return null;
        }

        return array_shift($this->post_queue);
    }

    /**
     * 全部のキューから優先順番でSqlオブジェクトを取得
     * @return mixed|null
     */
    public function deAllQueue(){
        $sql = $this->dePreQueue();

        if($sql != null){
            return $sql;
        }

        $sql = $this->deQueue();

        if($sql != null){
            return $sql;
        }

        $sql = $this->dePostQueue();

        if($sql != null){
            return $sql;
        }

        return null;
    }

    public function &getEntity(){
        return $this->entity;
    }
}