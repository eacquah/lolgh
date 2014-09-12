<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 12/09/14
 * Time: 14:37
 */

namespace Classes;


class User
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int
     */
    protected $dataAdded;

    /**
     * @param int $dataAdded
     */
    public function setDataAdded($dataAdded)
    {
        $this->dataAdded = $dataAdded;
    }

    /**
     * @return int
     */
    public function getDataAdded()
    {
        return $this->dataAdded;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

} 