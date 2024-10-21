<?php
namespace App\Http\Controllers\Api;

interface CommentApiControllerInterface{
    const CONTROLLER_NAME = 'Api\CommentApiController';

    const PAPER_ADD_COMMENT = 'paperAddComment';
    const PAPER_REPLY_COMMENT = 'paperReplayComment';
    const API_COMMENT_LIKE = 'commentLike';

    function paperAddComment(int $paper_id);

    function paperReplayComment(int $comment_id);

    function commentLike(int $comment_id);
}
