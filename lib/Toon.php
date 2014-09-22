<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 12/09/14
 * Time: 14:37
 */

namespace Lib;


class Toon
{
    /**
     * @var int
     */
    protected $toonId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $dateAdded;

    /**
     * @var int
     */
    protected $releaseDate;

    /**
     * @param int $toonId
     */
    public function setToonId($toonId)
    {
        $this->toonId = $toonId;
    }

    /**
     * @return int
     */
    public function getToonId()
    {
        return $this->toonId;
    }

    /**
     * @param int $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return int
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param int $releaseDate
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return int
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
} 