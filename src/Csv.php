<?php

namespace PhpCsv;

class Csv
{

    private $filename;

    /**
     * constructor
     * @param $filename
     */
    public function __construct($filename){
        $this->filename = $filename;
    }

    /**
     * ファイルを読み込む
     * @param $filename
     * @return array
     */
    function read($filename = null){

        if($filename == null){
            $filename = $this->filename;
        }

        if(!file_exists($filename)){
            return null;
        }

        $row_array = array();

        $handle = fopen($filename, 'r');

        if($handle){
            $line_no = 1;

            while(($line = fgets($handle)) !== false){

                //文字コードをSJISから変換
                mb_convert_variables("UTF-8", "SJIS", $line);
                
                $row_array[] = Row::withLine($line, $line_no);

                $line_no++;
            }
        }

        fclose($handle);
        
        $row_collection = new RowCollection($row_array);

        return $row_collection;
    }

    /**
     * ファイルを書き込む
     * @param $filename
     */
    function write($filename = null, RowCollection $row_collection){

        if($filename == null){
            $filename = $this->filename;
        }

        $filename.='_2';

        $handle = fopen($filename, 'w');

        if($handle){
            foreach($row_collection as $row){

                //文字コードをSJISに変換
                $str = mb_convert_encoding($row->getLine(),"SJIS","auto");

                fputs($handle, $str);
            }
        }

        fclose($handle);
    }

}