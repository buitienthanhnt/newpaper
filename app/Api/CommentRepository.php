<?php

namespace App\Api;

use App\Api\Data\Comment\CommentItem;
use App\Api\Data\Comment\CommentList;
use App\Api\Data\Page\PageInfo;
use App\Models\Comment;
use App\Models\CommentInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository{
    
    function __construct()
    {
        
    }

    /**
     * @param Comment $comment
     */
    function convertCommentItem($comment) {
        $commentItem = new CommentItem();
        $commentItem->setId($comment->id);
        $commentItem->setName($comment->{CommentInterface::ATTR_NAME});
        $commentItem->setContent($comment->{CommentInterface::ATTR_CONTENT});
        $commentItem->setEmail($comment->{CommentInterface::ATTR_EMAIL});
        $commentItem->setChildrentCount($comment->getChildrentCount());
        return $commentItem;
    }

    /**
     * @param Collection
     * @return Comment[]
     */
    function convertCommentList($items){
        $comments = [];
        foreach ($items as $item) {
            $comments[] = $this->convertCommentItem($item);
        }
        return $comments;
    }

    function convertPageInfo($paginateDatas){
        $pageInfo = new PageInfo();
        $pageInfo->setCurrentPage($paginateDatas->currentPage());
        $pageInfo->setLastPage($paginateDatas->lastPage());
        $pageInfo->setPageNumber($paginateDatas->perPage());
        $pageInfo->setTotal($paginateDatas->total());
        return $pageInfo;
    }

    /**
     * @param LengthAwarePaginator $paginateDatas
     */
    function convertPaperPaginate($paginateDatas) {
        $commentList = new CommentList();
        $commentList->setItems($this->convertCommentList($paginateDatas->items()));
        $commentList->setPageInfo($this->convertPageInfo($paginateDatas));
        return $commentList;
    }
}
