<?php

namespace App\Api\Data\Paper;

use Illuminate\Contracts\Support\Arrayable;

interface BaseAttributeInterface extends Arrayable
{
    const ID = 'id';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

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
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt(string $created_at);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt(string $updated_at);

    /**
     * @return string
     */
    public function getUpdatedAt();
}
