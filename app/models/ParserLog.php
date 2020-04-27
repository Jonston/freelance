<?php

namespace App\Models;

use NilPortugues\Sql\QueryBuilder\Syntax\OrderBy;

class ParserLog extends AbstractModel
{
    protected $table = 'parser_log';

    /**
     * @param string $type
     * @return mixed
     */
    public function getPages(string $type)
    {
        $sql = $this->builder
            ->select()
            ->setTable($this->table)
            ->where()
            ->equals('type', $type)
            ->end();

        return $this->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $type
     * @return mixed
     */
    public function getFirstPage(int $type)
    {
        $sql = "SELECT page, created_at AS `first`
          FROM parser_log 
          WHERE `type` = {$type} 
          ORDER BY created_at ASC LIMIT 1";

        return $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $type
     * @return mixed
     */
    public function getLastPage(int $type)
    {
        $sql = "SELECT page, created_at AS `last`
          FROM parser_log 
          WHERE `type` = {$type} 
          ORDER BY created_at DESC LIMIT 1";

        return $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $type
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function countRequests(int $type, string $from, string $to)
    {
        $sql = "SELECT COUNT(*) AS total
          FROM parser_log
          WHERE `type` = {$type}
          AND created_at BETWEEN '{$from}' AND '{$to}'";

        return $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }
}