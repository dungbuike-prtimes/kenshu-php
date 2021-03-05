<?php
include_once __DIR__.'/../Models/Post.php';
include_once __DIR__.'/../Helper/AuthHelper.php';
class PostController extends BaseController
{
    public function getPostByOwner() {
        $owner_id = AuthHelper::getUserId();
        $model = new Post();
        $posts = $model->getPostByOwner($owner_id);

        var_dump($posts);
    }


}