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
    public function setDb(Db $db) {
        $this->db = $db;
    }

    /**
     * @return MyPDO
     * @throws Exception
     */
    public function getDb() {
        if ($this->db === null) {
            throw new Exception ('Db has not been set yet');
        }
        return $this->db;
    }

    /**
     *
     */
    public function fetchAllComics()
    {
        $query = "SELECT * FROM comic";
        $results = $this->getDb()->query($query);
        var_dump($results);
    }

    /**
     *
     */
    public function fetchAllUsers()
    {
        $query = "SELECT * FROM users";
        $results = $this->getDb()->query($query);
        var_dump($results);
    }

} 