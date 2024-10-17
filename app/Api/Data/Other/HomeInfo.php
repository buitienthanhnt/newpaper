<?php
namespace App\Api\Data\Other;

use App\Api\Data\Attribute;

class HomeInfo extends Attribute implements HomeInfoInterface
{
	function setHit($hit)
    {
        // TODO: Implement setHit() method.
        return $this->setData(self::HIT, $hit);
    }

    function getHit()
    {
        return $this->getData(self::HIT);
        // TODO: Implement getHit() method.
    }

    public function setForward($forward)
    {
        return $this->setData(self::FORWARD, $forward);
        // TODO: Implement setForward() method.
    }

    public function getForward()
    {
        return $this->getData(self::FORWARD);
        // TODO: Implement getForward() method.
    }

    public function getMostPopulator()
    {
        return $this->getData(self::MOST_POPULATOR);
        // TODO: Implement getMostPopulator() method.
    }

    public function setMostPopulator($most_populator)
    {
        return $this->setData(self::MOST_POPULATOR, $most_populator);
        // TODO: Implement setMostPopulator() method.
    }

    public function getListImages()
    {
        return $this->getData(self::LIST_IMAGES);
        // TODO: Implement getListImages() method.
    }

    public function setListImages($list_images)
    {
        return $this->setData(self::LIST_IMAGES, $list_images);
        // TODO: Implement setListImages() method.
    }

    public function getMostRecents()
    {
        return $this->getData(self::MOST_RECENTS);
        // TODO: Implement getMostRecents() method.
    }

    public function setMostRecents($most_recents)
    {
        return $this->setData(self::MOST_RECENTS, $most_recents);
        // TODO: Implement setMostRecents() method.
    }

    public function getSearch()
    {
        return $this->getData(self::SEARCH);
        // TODO: Implement getSearch() method.
    }

    public function setSearch($search)
    {
        return $this->setData(self::SEARCH, $search);
        // TODO: Implement setSearch() method.
    }

    public function getTimeLine()
    {
        return $this->getData(self::TIME_LINE);
        // TODO: Implement getTimeLine() method.
    }

    public function setTimeLine($time_line)
    {
        return $this->setData(self::TIME_LINE, $time_line);
        // TODO: Implement setTimeLine() method.
    }

    public function getWriters()
    {
        return $this->getData(self::WRITERS);
        // TODO: Implement getWriters() method.
    }

    public function setWriters($writers)
    {
        return $this->setData(self::WRITERS, $writers);
        // TODO: Implement setWriters() method.
    }

    public function getMap()
    {
        return $this->getData(self::MAP);
        // TODO: Implement getMap() method.
    }

    public function setMap($map)
    {
        return $this->setData(self::MAP, $map);
        // TODO: Implement setMap() method.
    }

    public function getVideo()
    {
        return $this->getData(self::VIDEO);
        // TODO: Implement getVideo() method.
    }

    public function setVideo($video)
    {
        return $this->setData(self::VIDEO, $video);
        // TODO: Implement setVideo() method.
    }
}
