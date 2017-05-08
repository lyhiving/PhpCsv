<?php

namespace PhpCsv\Core;

use PhpCsv\Csv;
use PhpCsv\Query\Builder;
use PhpCsv\Row;

class Model
{
    protected $dir = '<your csv root dir>';  //アクセスフォルダー

    protected $csv;                     //csvオブジェクト

    protected $table;                   //テーブル名
    
    protected $row_collection;          //読み込んだデータ

    protected $validator;               //検証用オブジェクト
    protected $presenter;               //出力用オブジェクト
    protected $repository;              //データ取得用オブジェクト

    protected $hasTablePrefix = false;  //テーブル名の中にprefixが存在してるの
    protected $hasTablepostfix = false; //テーブル名の中にpostfixが存在してるの

    protected $sub_class_array = array(
        'validator',
        'presenter',
        'repository',
    );

    /**
     * Constructorにテーブル名設定
     * @param string $prefix
     * @param string $postfix
     */
    public function __construct($prefix = '', $postfix = ''){

        if($this->hasTablePrefix) {
            //prefixを付ける
            if ($prefix != '') {
                $this->table = $prefix . '_' . $this->table;
            }
        }

        if($this->hasTablepostfix) {
            //postfixを付ける
            if ($postfix != '') {
                $this->table = $this->table . '_' . $postfix;
            }
        }

        $this->table = $this->table.'.csv';
    }

    /**
     * CSVを読み込む
     */
    public function load(){
        $filename = $this->getFilename();

        echo $filename.'<p>';

        $this->csv = new Csv($filename);
        $this->row_collection = $this->csv->read();
    }

    /**
     * CSV内容を保存
     */
    public function save(){
        $filename = $this->getFilename();

        $this->csv->write($filename, $this->getRowCollection());
    }

    /**
     * CSV所属fullファイル名を取得
     * @return string
     */
    public function getFilename(){
        return $this->dir.'/'.$this->table;
    }

    /**
     * Builder共通のfunction call処理
     * @param $func
     * @param $args
     * @return Builder|bool
     */
    public function __call($func, $args){

        // sub classの取得
        if(in_array($func, $this->sub_class_array)) {

            if(!isset($this->$func)){
                $name = get_class($this);

                $class_name = preg_replace('/Model$/', ucfirst($func), $name);

                $this->$func = new $class_name($this);
            }

            return $this->$func;

        }
        // 既存なfunctionを使う
        else if(method_exists($this, $func)){
            call_user_func($func, $args);
        }
        //SQL Buildを実行
        else{
            $builder = new Builder($this);

            if(method_exists($builder, $func)){
                call_user_func_array(array($builder, $func), $args);
            }

            return $builder;
        }

        return false;
    }

    /**
     * オブジェクトの設定
     * @param $instance
     */
    public function set($instance){
        $set_rows = array();

        if($instance instanceof Row){
            $set_rows[] = $instance;

        }else if(is_array($instance)){
            $set_rows = $instance;
        }

        foreach($set_rows as $row){
            if($row->getLineNo() != 0){
                //既存のrow
                $this->getRowCollection()->set($row);
            }else{
                //新規のrow
                $this->getRowCollection()->add($row);
            }
        }
    }

    /**
     * CSV内容の配列を取得
     * @return mixed
     */
    public function get(){
        return $this->getRowCollection()->getAll();
    }

    /**
     * 新しいROWを作成する
     */
    public function create(){
        return Row::withHeader($this->header_array);
    }

	private function &getRowCollection(){
		if(!isset($this->row_collection)){
			$this->load();
		}

		return $this->row_collection;
	}

}