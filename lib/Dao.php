<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 12/09/14
 * Time: 14:37
 */

namespace Lib;

use \Lib\User as User;
use \Lib\Comic as Comic;
use \Lib\Db as Db;
use \Exception as Exception;

/**
 * Class Dao
 *
 * @package Lib
 */
class Dao
{
    /**
     * @var \Lib\MyPDO
     */
    protected $db;

    /**
     * @param Db $db
     */
    public function setDb(Db $db)
    {
        $this->db = $db;
    }

    /**
     * @return MyPDO
     * @throws Exception
     */
    public function getDb()
    {
        if ($this->db === null) {
            throw new Exception ('Db has not been set yet');
        }

        return $this->db;
    }

    /**
     * Converts underscores to camelcase
     *
     * @param string $value
     *
     * @return string
     */
    public function convertToCamelCase($value)
    {
        return preg_replace("/\_(.)/e", "strtoupper('\\1')", $value);
    }

    /**
     * Builds method name from field
     *
     * @param $dbField
     *
     * @return string
     */
    public function getMethodName($dbField)
    {
        return 'set' . ucfirst($this->convertToCamelCase($dbField));
    }

    /**
     * @param           $className
     * @param \stdClass $row
     *
     * @return mixed
     */
    public function build($className, \stdClass $row)
    {
        $obj = new $className();
        foreach ($row as $field => $value) {
            $method = $this->getMethodName($field);
            if (method_exists($obj, $method)) {
                $obj->$method($value);
            }
        }

        return $obj;
    }


    /**
     * @param string $table
     * @param int    $id
     *
     * @return array
     */
    public function findById($table, $id)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      WHERE $index = $id";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * Fetches all results from a table as objects
     *
     * @param $table
     *
     * @return array
     */
    public function fetchAll($table)
    {
        $results   = array();
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table";
        $rows      = $this->getDb()->get_rows($query);
        foreach ($rows as $row) {
            $results[] = $this->build($className, $row);
        }

        return $results;
    }

    /**
     * Fetches the most recent result from a table as an object
     *
     * @param $table
     *
     * @return array
     */
    public function fetchRecent($table)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                        ORDER BY $index DESC
                        LIMIT 1";
        $row       = $this->getDb()->get_row($query);
        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * Fetches the first result from a table as an object
     *
     * @param $table
     *
     * @return array
     */
    public function fetchFirst($table)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                        ORDER BY $index ASC
                        LIMIT 1";
        $row       = $this->getDb()->get_row($query);
        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    public function fetchNext($table, $id)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      WHERE $index > $id
                      ORDER BY $index ASC
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    public function fetchPrevious($table, $id)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      WHERE $index < $id
                      ORDER BY $index DESC
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    public function fetchRandom($table)
    {
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      ORDER BY RANDOM()
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    public function getTotal($table)
    {
        $query = "SELECT COUNT(*) AS total FROM $table";
        $row   = $this->getDb()->get_row($query);

        return (int)$row->total;
    }
} 