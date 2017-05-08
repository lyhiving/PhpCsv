<?php

namespace PhpCsv\Query\Sql;

use PhpCsv\Query\Sql\Core\ISql;

class Where extends ISql
{
    private $column;
    private $operator;
    private $value;

    /**
     * 値を設定する
     * @param ...$args
     */
    public function set(...$args){
        $this->column = $args[0];
        $this->operator = $args[1];
        $this->value = $args[2];
    }

    /**
     * 実行の際にやること
     * @param $row_array
     * @return array
     */
    public function &execute(&$row_array){

        foreach($row_array as $key => $row){

            if($row->isComment()){
                unset($row_array[$key]);
            }else{
				
				$result = true;

				if($this->column instanceof \Closure){

					$closure = $this->column;
					
					if(!$closure($row)){
						$result = false;
					}
					
				}else {

					$compare_val = $row->get($this->column);

					switch ($this->operator) {
						case '=':
							$result = ($compare_val == $this->value);
							break;
						case '>':
							$result = ($compare_val > $this->value);
							break;
						case '<':
							$result = ($compare_val < $this->value);
							break;
						case '>=':
							$result = ($compare_val >= $this->value);
							break;
						case '<=':
							$result = ($compare_val <= $this->value);
							break;
						default:
							$result = preg_match($this->operator, $compare_val, $m);
					}
				}
				
				if (!$result) {
					unset($row_array[$key]);
				}
				
            }
        }

        return $row_array;
    }
}