<?php

namespace App\Helper;


use App\Entity\Article\ArticleComment;

class ArticleCommentHelper
{
    public function parseComments($comments)
    {
        $parsedComments = [];

        /** @var ArticleComment $comment */
        foreach ($comments as $comment){
            $parsedComments[$comment->getId()] = $comment->toArray();
        }

        return $parsedComments;
    }
}