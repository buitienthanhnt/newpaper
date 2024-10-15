<?php
namespace App\Api\Data\Paper;

interface TagInterface{
    
    const ID = "id";
    const ENTITY_ID = "entityId";
    const VALUE = "value";
    const TYPE = "type";

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $entity
     * @return $this
     */
    public function setEntityId(int $entity);

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param string $value
     * @return $this
     */
    function setValue(string $value);

    /**
     * @return string
     */
    function getValue();

    /**
     * @param string $type
     * @return $this
     */
    function setType(string $type);

    /**
     * @return string
     */
    function getType();
}