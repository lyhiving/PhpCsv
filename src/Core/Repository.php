<?php

namespace PhpCsv\Core;


abstract class Repository
{
    protected $model = null;

    public function __construct($model){
        $this->model = $model;
    }

    /**
     * カラムの中に値は存在するチェック
     * @param $key
     * @param $val
     * @return bool
     */
    public function isExisted($key, $val){
        $count = $this->model->select()->where($key, '=', $val)->count()->done();

        if($count == 0){
            return false;
        }

        return true;
    }
    
    /**
     * idより選択する
     * @param $id
     * @return mixed
     */
    public function id($id){
        return $this->model->select()->where('id', '=', $id)->first()->done();
    }
    
    /**
     * 全部データを取得
     * @param $column
     * @param $value
     * @return mixed
     */
    public function getAll($column, $value){
        return $this->model->select()->where($column, '=', $value)->done();
    }
    
    /**
     * 指定のkeyより値一致のデータを取得する
     * @param $column
     * @param $value
     * @return mixed
     */
    public function get($column, $value){
        return $this->model->select()->where($column, '=', $value)->first()->done();
    }

}