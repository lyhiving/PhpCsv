<?php

namespace PhpCsv;


class RowCollection implements \Iterator
{
    protected $row_array;               //読み込んだデータ
    protected $header;
    
    private $index = 0;
    
    
    public function __construct(array $row_array){
        $this->row_array = array();
        
        foreach($row_array as $row){
            $this->add($row);


            if($row->isHeader()){
                $this->header = new Header($row);
            }
        }

        if($this->header){
            foreach($this->row_array as &$row){
                $row->setHeader($this->header);
            }
        }
    }
    
    public function set(Row $row){
        $this->row_array[$row->getLineNo()] = $row;
    }

    public function add(Row $row){
        $this->row_array[] = $row;
    }
    
    public function del(Row $row){
        $this->row_array[$row->getLineNo()] = null;
    }
    
    public function get($key){
        return $this->row_array[$key];
    }
    
    public function getAll(){
        return $this->row_array;
    }

    /**
     * データを廃棄する
     */
    public function destroy(){
        $this->header = null;
        
        foreach($this->row_array as $key => $row){
            $this->row_array[$key] = null;
        }
        $this->row_array = null;
    }


    /**
     * Iterator implement
     * @return mixed
     */
    public function &current(){
        return $this->row_array[$this->index];
    }

    /**
     * Iterator implement
     */
    public function next(){
        $this->index ++;
    }

    /**
     * Iterator implement
     * @return int
     */
    public function key(){
        return $this->index;
    }

    /**
     * Iterator implement
     * @return bool
     */
    public function valid(){
        return isset($this->row_array[$this->key()]);
    }

    /**
     * Iterator implement
     */
    public function rewind(){
        $this->index = 0;
    }

    /**
     * Iterator implement
     */
    public function reverse(){
        $this->row_array = array_reverse($this->row_array);
        $this->rewind();
    }
}