<?php
namespace App\Api;

use App\Models\Comment;
use App\Models\Paper;

class CommentApi{
    protected $comment;

    protected $commentRepository;

    function __construct(
        Comment $comment,
        CommentRepository $commentRepository
    )
    {
        $this->comment = $comment;
        $this->commentRepository = $commentRepository;
    }

    function getCommentOfPaper($paper_id) {
        /**
         * @var Paper $paper
         */
        $paper = Paper::find($paper_id);
        $comments = $paper->getCommentPaginate();
        return $this->commentRepository->convertPaperPaginate($comments);
    }

    function getCommentChildrent(int $comment_id) {
        /**
         * @var Comment $comment
         */
        $comment = $this->comment->find($comment_id);
        if (!$comment) {
            return response('', 405);
        }
        $childrent = $comment->getChildrentPaginate();
        return $this->commentRepository->convertPaperPaginate($childrent);
    }
}
