<?php
namespace App\Api;

use App\Models\Comment;
use App\Models\Paper;
use Illuminate\Http\Request;

class CommentApi{
    protected $comment;

    protected $commentRepository;
    protected $responseApi;

    protected $request;

    function __construct(
        Comment $comment,
        CommentRepository $commentRepository,
        ResponseApi $responseApi,
        Request $request
    )
    {
        $this->comment = $comment;
        $this->commentRepository = $commentRepository;
        $this->responseApi = $responseApi;
        $this->request = $request;
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

    function addComment(){

    }
}
