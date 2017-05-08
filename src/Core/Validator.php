<?php

namespace PhpCsv\Core;


class Validator
{
    protected $model = array();

    //汎用モデルのテストケース
    protected $generic_test_desc = array(
        'Generic\Id' => array()
    );

    //専用モデルのテストケース
    protected $proprietary_test_desc = array();

    public function __construct($model){
        $this->model = $model;
    }


    /**
     * 共通の部分をテスト
     * @return array
     */
    public function testGeneric(){
        return $this->runTest($this->generic_test_desc);
    }

    /**
     * 専用の部分をテスト
     * @return array
     */
    public function testProprietary(){
        return $this->runTest($this->proprietary_test_desc);
    }

    /**
     * 全体テスト
     * @return array
     */
    public function testAll(){
        $desc = array_merge($this->generic_test_desc, $this->proprietary_test_desc);

        return $this->runTest($desc);
    }

    /**
     * テストを行います
     * @param $test_case_desc
     * @return array
     */
    private function runTest($test_case_desc){
        $test_cases = array();

        foreach($test_case_desc as $test_case_name => $options){
            $class = '\\App\\Libs\\Validation\\Model\\'.$test_case_name.'Test';

            $test_cases[] = new $class($this->model, $options);
        }

        foreach($test_cases as &$test_case){
            $test_case->run();

            if($test_case->getResult()){
                echo $test_case->getName()." result: pass";
            }else{
                echo $test_case->getName()." result: fail";
            }
        }

        return $test_cases;
    }


    public function __call($func, $args){
        if(start_with($func, 'check')){
            $options = $args[0];

            $test = str_replace($func, 'check', '').'Test';

            $class = 'App\\Libs\\Validation\\Model\\'.$test.'Test';

            $test_case = new $class($this->model, $options);

            $test_case->run();

            return $test_case;
        }
    }
}