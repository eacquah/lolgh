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
     * @param null $where
     * @param null $orderBy
     * @return array
     */
    public function fetchAll($table, $where = null, $orderBy = null)
    {
        $results   = array();
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table $where $orderBy";
        $rows      = $this->getDb()->get_rows($query);
        foreach ($rows as $row) {
            $results[] = $this->build($className, $row);
        }

        return $results;
    }

  /**
   * Fetches all results in batches from a table as objects
   *
   * @param     $table
   * @param int $offset
   * @param int $limit
   *
   * @return array
   * @throws Exception
   */
  public function fetchBatch($table, $offset = 0, $limit = 50)
  {
    $results   = array();
    $className = '\Lib\\' . ucfirst(strtolower($table));
    $query     = "SELECT * FROM $table
                  LIMIT $offset, $limit";
    $rows      = $this->getDb()->get_rows($query);
    foreach ($rows as $row) {
      $results[] = $this->build($className, $row);
    }

    return $results;
  }

  /**
   * Fetches all released results from a table as objects
   *
   * @param $table
   *
   * @return array
   */
  public function fetchReleased($table)
  {
    $results   = array();
    $className = '\Lib\\' . ucfirst(strtolower($table));
    $query     = "SELECT * FROM $table
                WHERE release_date <= date()";
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
                        WHERE release_date <= date()
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
     * @return mixed|null
     */
    public function fetchRecentComic()
    {
        $className = '\Lib\\' . ucfirst(strtolower('comic'));
        $query     = "SELECT * FROM comic
                        WHERE release_date <= date()
                        ORDER BY date(release_date) DESC, comic_id DESC
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
                        WHERE release_date <= date()
                        ORDER BY $index ASC
                        LIMIT 1";
        $row       = $this->getDb()->get_row($query);
        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * @return mixed|null
     */
    public function fetchFirstComic()
    {
        $className = '\Lib\\' . ucfirst(strtolower('comic'));
        $query     = "SELECT * FROM comic
                        WHERE release_date <= date()
                        ORDER BY date(release_date) ASC, comic_id ASC
                        LIMIT 1";
        $row       = $this->getDb()->get_row($query);
        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * @param $table
     * @param $id
     * @return mixed|null
     */
    public function fetchNext($table, $id)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      WHERE $index > $id
                      AND release_date <= date()
                      ORDER BY $index ASC
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * @param $releaseDate
     * @return mixed|null
     */
    public function fetchNextComic($releaseDate)
    {
        $className = '\Lib\\' . ucfirst(strtolower('comic'));
        $query     = "SELECT * FROM comic
                      WHERE $releaseDate > release_date
                      AND release_date <= date()
                      ORDER BY date(release_date) ASC, comic_id ASC
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * @param $table
     * @param $id
     * @return mixed|null
     */
    public function fetchPrevious($table, $id)
    {
        $index     = strtolower($table) . '_id';
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      WHERE $index < $id
                      AND release_date <= date()
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
     * @param $releaseDate
     * @return mixed|null
     */
    public function fetchPreviousComic($releaseDate)
    {
        $className = '\Lib\\' . ucfirst(strtolower('comic'));
        $query     = "SELECT * FROM comic
                      WHERE $releaseDate < release_date
                      AND release_date <= date()
                      ORDER BY date(release_date) DESC, comic_id DESC
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * @param $table
     * @return mixed|null
     */
    public function fetchRandom($table)
    {
        $className = '\Lib\\' . ucfirst(strtolower($table));
        $query     = "SELECT * FROM $table
                      WHERE release_date <= date()
                      ORDER BY RANDOM()
                      LIMIT 1";
        $row       = $this->getDb()->get_row($query);

        if ($row) {
            return $this->build($className, $row);
        } else {
            return null;
        }
    }

    /**
     * @param $table
     * @return int
     */
    public function getTotal($table)
    {
        $query = "SELECT COUNT(*) AS total FROM $table";
        $row   = $this->getDb()->get_row($query);

        return (int)$row->total;
    }

    /**
     * @param $email
     * @param $password
     * @return null
     */
    public function authenticate($email, $password)
    {
        $passwordHash = new Password();
        $query     = "SELECT * FROM user
                      WHERE email = '$email'";
        $row       = $this->getDb()->get_row($query);
        if ($row && $passwordHash->verify($password, $row->password)) {
            return $row->user_id;
        } else {
            return null;
        }
    }
} 