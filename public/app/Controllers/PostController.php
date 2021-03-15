<?php
include_once __DIR__ . '/../Models/Post.php';
include_once __DIR__ . '/../Models/Tag.php';
include_once __DIR__ . '/../Helper/AuthHelper.php';
include_once __DIR__ . '/../Helper/FileUploadHelper.php';
include_once __DIR__ . '/../Helper/InputHelper.php';
include_once __DIR__ . '/../Helper/Csrf.php';

include_once 'BaseController.php';

class PostController extends BaseController
{

    function __construct()
    {
        parent::__construct();
    }

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
        $Tag_model = new Tag();
        $tags = $Tag_model->getAll();
        $data['tags'] = $tags;

        $csrf = new Csrf();
        $data['csrf_token'] = $csrf->generateToken();

        $this->view('post/create', $data);
    }

    public function store()
    {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        $csrf = new Csrf();
        $csrf->verify();

        if (empty($_POST['title']) || empty($_POST['content'] == '')) {
            return $this->message('error', '400', 'Title and Content is required!')
                ->view('post/create', $data);
        }
        if (!FileUploadHelper::fileValidate($_FILES)) {
            return $this->message('error', '400', 'Upload file failed!')
                ->view('post/create', $data);
        }

        $owner_id = AuthHelper::getUserId();

        try {
            $params['owner'] = InputHelper::int($owner_id);
            $params['title'] = InputHelper::str($_POST['title']);
            $params['content'] = InputHelper::str($_POST['content']);
        } catch (Exception $e) {
            $this->message('error', '400', 'Invalid input!');
            return header('location:/posts');
        }

        $Post_model = new Post();
        PDO::beginTransaction();
        try {
            $post = $Post_model->create($params);
            FileUploadHelper::handleFileUpload($_FILES, $post);

            for ($i = 0; $i < count($_POST['tag']); $i++) {
                $Post_model->insertTag($post, h($_POST['tag'][$i]));
            }
        } catch (PDOException $e) {
            PDO::rollback();
            $this->message('error', '500', 'Database error. Create failed!');
            return header('location:/posts');

        }
        PDO::commit();

        $this->message('success', '200', 'Create!');
        return header('location:/posts');

    }

    public function edit($id)
    {
        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        $csrf = new Csrf();
        $data['csrf_token'] = $csrf->generateToken();
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        $Post_model = new Post();
        $current_user = AuthHelper::getUserId();
        $post = $Post_model->getPost(h($id));

        if (empty($post) || (!empty($post) && $post["OWNER"] != $current_user)) {
            return header('Location:/posts');
        }

        $data['post'] = $post;
        return $this->view('post/edit', $data);
    }

    public function update($id)
    {
        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        $csrf = new Csrf();
        $csrf->verify();

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
            return $this->message('error', '400', 'Title and Content is required!')
                ->view('post/edit', $data);
        }
        if (!FileUploadHelper::fileValidate($_FILES)) {
            return $this->message('error', '400', 'File not allowed!')
                ->view('post/edit', $data);
        }

        $owner_id = AuthHelper::getUserId();

        try {
            $params['owner'] = InputHelper::int($owner_id);
            $params['title'] = InputHelper::str($_POST['title']);
            $params['content'] = InputHelper::str($_POST['content']);
        } catch (Exception $e) {
            $this->message('error', '400', 'Invalid input!');
            return header('location:/posts');
        }

        $db = $Post_model->db->database;
        try {
            $db->beginTransaction();
            $post = $Post_model->update($id, $params);
            if (isset($_POST['deleteImage'])) {
                foreach ($_POST['deleteImage'] as $img) {
                    $Post_model->deleteImage(h($img));
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
            var_dump($db->inTransaction());
//            $db->commit();
        } catch (PDOException $e) {
//            $db->rollBack();
            $this->message('error', '500', 'Database error. Create failed!'. $e);
            return header('location:/posts');

        }

        $data = [];
        $post = $Post_model->getPost($id);

        $data['post'] = $post;
        $data['tags'] = $tags;

        return $this->message("success", "200", "Updated!")->view('post/edit', $data);
    }

    public function delete($id)
    {
        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        $csrf = new Csrf();
        $csrf->verify();

        $current_user = AuthHelper::getUserId();
        $Post_model = new Post();
        $post = $Post_model->getPost($id);
        if (empty($post) || (!empty($post) && ($post["OWNER"] != $current_user))) {
            return header('Location:/posts');
        }
        $Post_model->deletePost($id);

        $posts = $Post_model->getPostByOwner($current_user);

        $data['posts'] = $posts;
        $this->message("success", "200", "Post is deleted");

        return header('location:/posts');

    }

    public function show($id)
    {

        $csrf = new Csrf();
        $data['csrf_token'] = $csrf->generateToken();

        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }

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