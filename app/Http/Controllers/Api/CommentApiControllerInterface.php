<?php
namespace App\Http\Controllers\Api;

interface CommentApiControllerInterface{
    const CONTROLLER_NAME = 'Api\CommentApiController';

    const PAPER_ADD_COMMENT = 'paperAddComment';
    const PAPER_REPLY_COMMENT = 'paperReplayComment';

    function paperAddComment(int $paper_id);

    function paperReplayComment(int $comment_id);
}
