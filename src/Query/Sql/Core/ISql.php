<?php

namespace PhpCsv\Query\Sql\Core;

abstract class ISql
{
    protected $entity;

    /**
     * SQL実行の状態を取得します
     * @return mixed
     */
    public function &getEntity()
    {
        return $this->entity;
    }

    /**
     * SQL実行の状態をセーブします
     * @param mixed $entity
     */
    public function setEntity(&$entity)
    {
        $this->entity = &$entity;
    }

    abstract public function set(...$args);
    abstract public function execute(&$row_array);


}