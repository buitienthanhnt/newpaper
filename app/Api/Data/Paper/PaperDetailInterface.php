<?php
namespace App\Api\Data\Paper;

interface PaperDetailInterface extends PaperItemInterface{
    const CONTENTS = 'contents';
    const SUGGEST = 'suggest';
    const INFO = 'info';
    const TAGS = 'tags';


    /**
     * @param mixed $contents
     * @return $this
     */
    public function setContents($contents);

    /**
     * @return mixed
     */
    public function getContents();

        /**
     * @param mixed $suggest
     * @return $this
     */
    public function setSuggest($suggest);

    /**
     * @return mixed
     */
    public function getSuggest();

        /**
     * @param \App\Api\Data\Paper\Info $info
     * @return $this
     */
    public function setInfo($info);

    /**
     * @return \App\Api\Data\Paper\Info
     */
    public function getInfo();

    /**
     * @param \App\Api\Data\Paper\Tag[] $tags
     * @return $this
     */
    public function setTags($tags);

    /**
     * @return \App\Api\Data\Paper\Tag[]
     */
    public function getTags();

}
?>