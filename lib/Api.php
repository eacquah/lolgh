<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 07/11/14
 * Time: 15:02
 */

namespace Lib;

class Api
{
    protected $dao;

    protected $method;

    protected $params;

    /**
     * @return mixed
     */
    public function getDao()
    {
      return $this->dao;
    }

  /**
   * @param mixed $dao
   *
   * @return $this
   */
    public function setDao($dao)
    {
      $this->dao = $dao;
      return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
      return $this->method;
    }

  /**
   * @param mixed $method
   *
   * @return $this
   */
    public function setMethod($method)
    {
      $this->method = $method;
      return $this;
    }

  /**
   * @return mixed
   */
  public function getParams() {
    return $this->params;
  }

  /**
   * @param mixed $params
   *
   * @return $this
   */
  public function setParams(array $params) {
    $this->params = $params;
    return $this;
  }

    public function processApi()
    {
      if (!$this->getMethod() || !$this->getParams()){
        header('HTTP/1.1 405 Method Not Allowed');
      }
      switch ($this->getMethod()) {
        case 'GET':
          $params = $this->getParams();
          if (in_array('comics', $params)) {
            $comics = $this->getDao()->fetchBatch('comic', 0, 20);
            $return = array();
            foreach ($comics as $comic) {
              $return[] = $comic->jsonSerialize();
            }
            echo json_encode($return);
            break;
          } elseif (in_array('toons', $params)) {
            $toons = $this->getDao()->fetchBatch('toon', 0, 5);
            $return = array();
            foreach ($toons as $toon) {
              $return[] = $toon->jsonSerialize();
            }
            echo json_encode($return);
          } else{
            header('HTTP/1.1 404 Not Found');
          }
          break;
        default:
          header('HTTP/1.1 405 Method Not Allowed');
          header('Allow: GET');
          break;
      }
    }

    public function get()
    {

    }
} 