<?php

namespace App\Http\Controllers\Api;

use App\Api\CommentApi;
use App\Api\CommentRepository;
use App\Api\Data\Response;
use App\Api\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Exception\FormValidationException;
use App\Http\Validation\CommentForm;
use App\Models\Comment;
use App\Models\CommentInterface;
use App\Models\Paper;
use Illuminate\Http\Request;

class CommentApiController extends Controller implements CommentApiControllerInterface
{
    protected $request;

    /**
     * @var Paper $paper
     */
    protected $paper;
    protected $comment;

    protected $response;
    protected $responseApi;
    protected $commentApi;

    protected $commentRepository;

    protected $commentForm;

    function __construct(
        Request $request,
        Paper $paper,
        Comment $comment,
        CommentApi $commentApi,
        CommentRepository $commentRepository,
        ResponseApi $responseApi,
        Response $response,
        CommentForm $commentForm
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->comment = $comment;
        $this->response = $response;
        $this->responseApi = $responseApi;
        $this->commentApi = $commentApi;
        $this->commentRepository = $commentRepository;
        $this->commentForm = $commentForm;
    }

    function paperAddComment($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        if (!$paper) {
            return response([
                'message' => 'paper does not exist!'
            ], 402);
        }
        if (
            (!$email = $this->request->get(CommentInterface::ATTR_EMAIL)) ||
            (!$name = $this->request->get(CommentInterface::ATTR_NAME)) ||
            (!$content = $this->request->get(CommentInterface::ATTR_CONTENT))
        ) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->response->setMessage('require data not found'));
        }
        $commentData = [
            CommentInterface::ATTR_EMAIL => $email,
            CommentInterface::ATTR_NAME => $name,
            CommentInterface::ATTR_PAPER_ID => $paper_id,
            CommentInterface::ATTR_CONTENT => $content,
            CommentInterface::ATTR_SHOW => true,
        ];
        try {
            $this->comment->fill($commentData)->save();
        } catch (\Exception $e) {
            $this->response->setMessage($e->getMessage());
            return $this->responseApi->setResponse($this->response)->setStatusCode(500);
        }
        return $this->responseApi->setResponse(
            $this->response->setResponse(
                $this->commentRepository->convertCommentItem($this->comment)
            )->setMessage('add comment success!')
        );
    }

    /**
     * @param int $comment_id
     * @return ResponseApi
     */
    function paperReplayComment(int $comment_id)
    {
        $comment = $this->comment->find($comment_id);
        if (!$comment) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->response->setMessage('comment does not exist'));
        }

        $commentData = [
            CommentInterface::ATTR_EMAIL => $this->request->get(CommentInterface::ATTR_EMAIL),
            CommentInterface::ATTR_NAME => $this->request->get(CommentInterface::ATTR_NAME),
            CommentInterface::ATTR_PARENT_ID => $comment_id,
            CommentInterface::ATTR_CONTENT => $this->request->get(CommentInterface::ATTR_CONTENT),
            CommentInterface::ATTR_SHOW => true,
            CommentInterface::ATTR_PAPER_ID => $comment->{CommentInterface::ATTR_PAPER_ID}
        ];

        try {
            $this->commentForm->validate($commentData);
            /**
             * gán tất cả data mới trên 1 model đã có và ghi đè ngữ cảnh nó luôn.
             * có thể hiểu là làm mới model đó luôn.
             */
            $this->comment->forceFill($commentData)->save();
        } catch (FormValidationException $e) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->response->setMessage($e->getFullMessage()));
        } catch (\Exception $e) {
            $this->response->setMessage($e->getMessage());
            return $this->responseApi->setResponse($this->response)->setStatusCode(500);
        }

        return $this->responseApi->setResponse(
            $this->response->setResponse(
                $this->commentRepository->convertCommentItem($this->comment)
            )->setMessage('add comment success!')
        );
    }
}
