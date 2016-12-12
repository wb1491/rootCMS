<?php

// +----------------------------------------------------------------------
// | rootCMS 评论行为Api
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 wb1491 All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Comments\Behavior;

class CommentsApi {

    //信息删除行为标签
    public function content_delete_end($data) {
        if (empty($data)) {
            return false;
        }
        $comment_id = "c-{$data['catid']}-{$data['id']}";
         model('Comments/Comments')->deleteCommentsMark($comment_id);
        return true;
    }

}
