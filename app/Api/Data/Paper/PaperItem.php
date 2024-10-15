<?php
namespace App\Api\Data\Paper;

class PaperItem extends BaseAttribute implements PaperItemInterface
{
    public function setTitle(string $title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    function setUrl(string $url)
    {
        return $this->setData(self::URL, $url);
    }

    function getUrl() {
        return $this->getData(self::URL);
    }

    function setShortContent(string $short_content)
    {
        return $this->setData(self::SHORT_CONTENT, $short_content);
    }

    function getShortContent()
    {
        return $this->getData(self::SHORT_CONTENT);
    }

    function setImage(string $image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    function setActive(bool $active)
    {
        return $this->setData(self::ACTIVE, $active);
    }

    function getActive()
    {
        return $this->getData(self::ACTIVE);
    }

    function setWriter($writer)
    {
        return $this->setData(self::WRITER, $writer);
    }

    function getWriter()
    {
        return $this->getData(self::WRITER);
    }
}
