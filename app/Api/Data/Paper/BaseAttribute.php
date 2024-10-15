<?php
namespace App\Api\Data\Paper;

use App\Api\Data\DataObject;
use Illuminate\Contracts\Support\Arrayable;

class BaseAttribute extends DataObject implements BaseAttributeInterface, Arrayable
{
    function setCreatedAt(string $created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    function setUpdatedAt(string $updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setId(int $id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }
}

?>