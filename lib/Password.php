<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 12/09/14
 * Time: 14:37
 */

namespace Lib;

class Password
{
    public function hash($password)
    {
        return sha1($password);
    }

    public function verify($password, $hashedPassword)
    {
        return (sha1($password) === $hashedPassword);
    }
} 