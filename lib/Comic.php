<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 12/09/14
 * Time: 14:37
 */

namespace Lib;


class Comic
{
    /**
     * @var int
     */
    protected $comicId;

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
     * @param int $comicId
     */
    public function setComicId($comicId)
    {
        $this->comicId = $comicId;
    }

    /**
     * @return int
     */
    public function getComicId()
    {
        return $this->comicId;
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