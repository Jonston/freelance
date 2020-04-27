<?php

namespace App\Models;

use Core\Service;

class AbstractModel
{
    protected $db;

    protected $builder;

    protected $table;

    public function __construct()
    {
        $this->builder = Service::get('builder');

        $this->db = Service::get('db');
    }

    /**
     * @param $sql
     * @return mixed
     */
    protected function execute($sql)
    {
        $stmt = $this->db->prepare($this->builder->write($sql));
        $stmt->execute($this->builder->getValues());

        return $stmt;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $sql = $this->builder->select()->setTable($this->table);

        return $this->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        $sql = $this->builder
            ->select()
            ->setTable($this->table)
            ->where()
            ->equals('id', $id)
            ->end();

        return $this->execute($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $column
     * @param $value
     * @return mixed
     */
    public function findByColumn(string $column, $value)
    {
        $sql = $this->builder
            ->select()
            ->setTable($this->table)
            ->where()
            ->equals($column, $value)
            ->end();

        return $this->execute($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $column
     * @param $value
     * @return mixed
     */
    public function exists(string $column, $value)
    {
        $sql = $this->builder->select()
            ->setTable($this->table)
            ->setFunctionAsColumn('COUNT', ['id'], 'total')
            ->where()
            ->equals($column, $value)
            ->end();

        return $this->execute($sql)->fetch(\PDO::FETCH_COLUMN);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insert($data)
    {
        $sql = $this->builder->insert()
            ->setTable($this->table)
            ->setValues($data);

        $this->execute($sql);

        return $this->db->lastInsertId();
    }
}