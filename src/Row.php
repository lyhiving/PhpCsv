<?php

namespace PhpCsv;

use PhpCsv\Core\Model;

class Row
{
    protected $orig_line = '';
    protected $orig_line_no = 0;
    protected $line = '';
    protected $line_no = 0;
    protected $data_array = null;

	protected $is_drity = false;

    protected $header = null;

    /**
     * Constructor
     * @param $line
     * @param $line_no
     */
    public function __construct(){

    }

    /**
     * Lineより新しいオブジェクトを作る
     * @param $line
     * @param $line_no
     * @return Row
     */
    public static function withLine($line, $line_no){
        $instance = new self();

        $instance->line = $line;
        $instance->line_no = $line_no;
        $instance->orig_line = $line;
        $instance->orig_line_no = $line_no;

        $instance->initializeArray();

        return $instance;
    }

    /**
     * Header情報
     * @param $header_array
     */
    public static function withHeader(Header $header){
        $instance = new self();

        $instance->line = 0;
        $instance->line_no = 0;
        $instance->orig_line = 0;
        $instance->orig_line_no = 0;
        $instance->header = $header;
        
        $instance->data_array = $header;
        foreach($instance->data_array as &$data){
            $data = '';
        }
        
        $instance->initializeLine();

        return $instance;
    }

    /**
     * ラインを出力
     * @return string
     */
    public function getLine(){
		if($this->is_drity){
			$this->initializeLine();

			$this->is_drity = false;
		}

        return $this->line;
    }

    /**
     * ラインの番号を取得
     * @return int
     */
    public function getLineNo(){
        return $this->line_no;
    }

    /**
     * ラインの番号を設定
     * @param $line_no
     */
    public function setLineNo($line_no){
        $this->line_no = $line_no;
    }

    /**
     * データ配列を取得
     * @return array
     */
    protected function &getDataArray(){
        return $this->data_array;
    }

    /**
     * headerを設定
     * @param $header_array
     */
    public function setHeader(Header &$header){
        $this->header = $header;
    }

    /**
     * headerとデータ関連の配列を取得
     * @return array
     */
    private function getRelationArray(){
        
        if($this->isComment()){
            return array();
        }

        $ret = array();
        
        $header_array = $this->header->getDataArray();
        
        foreach($header_array as $key => $header){
            if(isset($this->data_array[$key])) {
                $ret[$header] = $this->data_array[$key];
            }else{
                $ret[$header] = '';
            }
        }
        
        return $ret;
    }

    /**
     * getRelationArrayの簡略
     * @return array
     */
    public function getAll(){
        return $this->getRelationArray();
    }

    /**
     * カラムの値配列を設定する
     * @param $options
     * @return bool
     */
    private function setRelationArray($options){
        $mapping = $this->header->getReverseDataArray();

        foreach($options as $key => $val){

            if(isset($mapping[$key])) {
                $idx = $mapping[$key];

                $this->data_array[$idx] = $val;
            }
        }

		$this->is_drity = true;
    }

    /**
     * setRelationArrayの簡略
     * @param $options
     * @return bool
     */
    public function setAll($options){
        return $this->setRelationArray($options);
    }

    /**
     * 鍵より値を取得する
     * @param $key
     * @return null
     */
    private function getRelationValue($key){

        if(!isset($this->header)){
            return null;
        }

        $mapping = $this->header->getReverseDataArray();

        if(!isset($mapping[$key])) {
            return null;
        }
        
        $idx = $mapping[$key];
        
        return $this->data_array[$idx];
    }

    /**
     * getRelationValueの簡略
     * @param $key
     * @return null
     */
    public function get($key){
        return $this->getRelationValue($key);
    }

    /**
     * 鍵より値を設定する
     * @param $key
     * @param $value
     * @return null
     */
    private function setRelationValue($key, $value){
        if(!isset($this->header)){
            return null;
        }

        $mapping = $this->header->getReverseDataArray();

        if(!isset($mapping[$key])) {
            return null;
        }

        $idx = $mapping[$key];

        $this->data_array[$idx] = $value;

		$this->is_drity = true;

        return true;
    }

    /**
     * setRelationValueの簡略
     * @param $key
     * @param $value
     * @return null
     */
    public function set($key, $value){
        return $this->setRelationValue($key, $value);
    }

    /**
     * コメント判断
     * @return bool
     */
    public function isComment(){
        if($this->data_array[0] != '' && !starts_with($this->data_array[0], '#')){
            return false;
        }

        return true;
    }

    /**
     * ヘダー判断
     * @return bool
     */
    public function isHeader(){
        if(count($this->data_array) > 0 && $this->data_array[0] == 'id'){
            return true;
        }
        
        return false;
    }

    /**
     * データ内容は全部削除します
     */
    public function resetData(){

        foreach($this->data_array as &$data){
            $data = '';
        }

        $this->initializeLine();
    }

    /**
     * データが廃棄する
     */
    public function destroy(){
        $this->header = null;
    }

    /**
     * ラインから配列へ転換
     * @param $line
     * @return array
     */
    protected function initializeArray(){
        $this->data_array = preg_split("/[,\n]/", $this->line);

        //TODO test code
        foreach($this->data_array as $data){
            if(starts_with($data, '\"')){
                echo "<font color='red'>$this->line</font>";
            }
        }
    }

    /**
     * 配列からラインへ転換
     */
    protected function initializeLine(){
        $this->line = implode(',', $this->data_array)."\n";
    }
}