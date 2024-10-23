<?php

namespace App\Http\Controllers\Api;

interface PaperApiControllerInterface
{
    const CONTROLLER_NAME = 'Api\PaperApiController';

    const API_PAPER_ADD_LIKE = 'addPaperLike';
    const LIST_PAPERS = 'listPapers';
    const PAPER_DETAIL = 'getPaperDetail';
    const PAPER_RELATED = 'getRelatedPaper';

    function addPaperLike(int $paper_id);

    /**
     * @param int $paper_id
     * @return mixed
     */
    public function getPaperDetail(int $paper_id);

    public function listPapers();

     /**
     * @param int $paper_id
     */
    public function getRelatedPaper(int $paper_id);

}
