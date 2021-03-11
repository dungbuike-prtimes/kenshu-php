<?php
include_once __DIR__ . '/../Models/Post.php';
include_once __DIR__ . '/../Models/Tag.php';
include_once __DIR__ . '/../Helper/AuthHelper.php';
include_once __DIR__ . '/../Helper/FileUploadHelper.php';
include_once 'BaseController.php';

class PostController extends BaseController
{
    public function index()
    {
        $owner_id = AuthHelper::getUserId();
        $Post_model = new Post();
        $posts = $Post_model->getPostByOwner($owner_id);

        $data['posts'] = $posts;
        $this->view('post/index', $data);
    }

    public function create()
    {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;
        $this->view('post/create', $data);
    }

    public function store()
    {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        if ($_POST['title'] == '' || $_POST['content'] == '') {
            return $this->flash('error', '400', 'Title and Content is required!')
                ->view('post/create', $data);
        }
        if (!FileUploadHelper::fileValidate($_FILES)) {
            return $this->flash('error', '400', 'Upload file failed!')
                ->view('post/create', $data);
        }

        $owner_id = AuthHelper::getUserId();
        $params['owner'] = $owner_id;
        $params['title'] = $_POST['title'];
        $params['content'] = $_POST['content'];
        $Post_model = new Post();
        $post = $Post_model->create($params);

        FileUploadHelper::handleFileUpload($_FILES, $post);

        for ($i = 0; $i < count($_POST['tag']); $i++) {
            $Post_model->insertTag($post, $_POST['tag'][$i]);
        }

        $this->flash('success', '200', 'Create!');
        return header('location:/posts');

    }

    public function edit($id)
    {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        $Post_model = new Post();
        $current_user = AuthHelper::getUserId();
        $post = $Post_model->getPost($id);

        if (empty($post) || (!empty($post) && $post["OWNER"] != $current_user)) {
            return header('Location:/posts');
        }

        $data['post'] = $post;
        return $this->view('post/edit', $data);
    }

    public function update($id)
    {
        $current_user = AuthHelper::getUserId();
        $Tag = new Tag();
        $Post_model = new Post();
        $data = [];

        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        $post = $Post_model->getPost($id);
        $data['post'] = $post;

        if (empty($post) || (!empty($post) && $post["OWNER"] !== $current_user)) {
            return header('Location:/posts');
        }


        if ($_POST['title'] == '' || $_POST['content'] == '') {
            return $this->flash('error', '400', 'Title and Content is required!')
                ->view('post/create', $data);
        }

        if (!FileUploadHelper::fileValidate($_FILES)) {
            return $this->flash('error', '400', 'File not allowed!')
                ->view('post/create', $data);
        }

        $owner_id = AuthHelper::getUserId();
        $params['owner'] = $owner_id;
        $params['title'] = $_POST['title'];
        $params['content'] = $_POST['content'];
        $post = $Post_model->update($id, $params);


        if (isset($_POST['deleteImage'])) {
            foreach ($_POST['deleteImage'] as $img) {
                $Post_model->deleteImage($img);
            }
        }
        FileUploadHelper::handleFileUpload($_FILES, $id);

        $post_tags = $Post_model->getTagsOfPost($id);
        foreach ($post_tags as $post_tag) {
            $Post_model->deletePostTag($post_tag['id']);
        }

        for ($i = 0; $i < count($_POST['tags']); $i++) {
            $Post_model->insertTag($id, $_POST['tags'][$i]);
        }

        $data = [];
        $post = $Post_model->getPost($id);

        $data['post'] = $post;
        $data['tags'] = $tags;

        return $this->flash("success", "200", "Updated!")->view('post/edit', $data);
    }

    public function delete($id)
    {
        $current_user = AuthHelper::getUserId();
        $Post_model = new Post();
        $post = $Post_model->getPost($id);
        if (empty($post) || (!empty($post) && ($post["OWNER"] != $current_user))) {
            return header('Location:/posts');
        }
        $Post_model->deletePost($id);

        $posts = $Post_model->getPostByOwner($current_user);

        $data['posts'] = $posts;
        $this->flash("success", "200", "Post is deleted");

        return header('location:/posts');

    }

    public function show($id) {
        $current_user = AuthHelper::getUserId();
        $Post_model = new Post();
        $post = $Post_model->getPost($id);
        if (empty($post) || (!empty($post) && $post["OWNER"] !== $current_user)) {
            return header('Location:/posts');
        }
        $data['post'] = $post;

        return $this->view('post/show', $data);

    }
}