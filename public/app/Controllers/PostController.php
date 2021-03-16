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
        $data['posts'] = $Post_model->getPostByOwner($owner_id);
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
        $csrf = new Csrf();
        $Post_model = new Post();
        $current_user = AuthHelper::getUserId();
        $params = [];

        $tags = $Tag->getAll();
        $data['tags'] = $tags;
        $csrf->verify();

        if (empty($_POST['title']) || empty($_POST['content'])) {
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', '400', 'Title and Content is required!')
                ->view('post/create', $data);
        }
        if (!FileUploadHelper::fileValidate($_FILES)) {
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', '400', 'Upload file failed!')
                ->view('post/create', $data);
        }
        try {
            $params['owner'] = InputHelper::int($current_user);
            $params['title'] = InputHelper::str($_POST['title']);
            $params['content'] = InputHelper::str($_POST['content']);
        } catch (Exception $e) {
            $data['csrf_token'] = $csrf->generateToken();
            $this->message('error', '400', 'Invalid input!')
                ->view('post/create', $data);
        }

        $db = $Post_model->db->database;
        try {
            $db->beginTransaction();
            $post = $Post_model->create($params);
            FileUploadHelper::handleFileUpload($_FILES, $post, $Post_model);
            for ($i = 0; $i < count($_POST['tag']); $i++) {
                $Post_model->insertTag($post, h($_POST['tag'][$i]));
            }
            $db->commit();
            $this->message('success', '200', 'Post is created!');
            return header('location:/posts');
        } catch (PDOException $e) {
            $db->rollBack();
            $this->message('error', '500', 'Database error. Create failed!');
            return header('location:/posts');
        }
    }

    public function edit($id)
    {
        $csrf = new Csrf();
        $Tag = new Tag();
        $Post_model = new Post();
        $params = [];
        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        $current_user = AuthHelper::getUserId();
        $post = $Post_model->getPost($params['owner']);

        if (empty($post) || ($post["OWNER"] != $current_user)) {
            return header('Location:/posts');
        }
        $data['post'] = $post;
        $data['csrf_token'] = $csrf->generateToken();
        return $this->view('post/edit', $data);
    }

    public function update($id)
    {
        $csrf = new Csrf();
        $Tag = new Tag();
        $Post_model = new Post();
        $current_user = AuthHelper::getUserId();
        $params = [];
        $data = [];

        $csrf->verify();
        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage())->view('post/edit');
            return header('location:/posts');
        }
        $tags = $Tag->getAll();
        $data['tags'] = $tags;
        $data['post'] = $Post_model->getPost($id);
        if (empty($data['post']) || ($data['post']["OWNER"] != $current_user)) {
            return header('Location:/posts');
        }
        if (empty($_POST['title']) || empty($_POST['content'])) {
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', '400', 'Title and Content is required!')
                ->view('post/create', $data);
        }
        if (!FileUploadHelper::fileValidate($_FILES)) {
            return $this->message('error', '400', 'File not allowed!')
                ->view('post/edit', $data);
        }
        try {
            $params['owner'] = InputHelper::int($current_user);
            $params['title'] = InputHelper::str($_POST['title']);
            $params['content'] = InputHelper::str($_POST['content']);
        } catch (Exception $e) {
            $data['csrf_token'] = $csrf->generateToken();
            $this->message('error', '400', 'Invalid input!')
                ->view('post/edit', $data);
        }

        $db = $Post_model->db->database;
        try {
            $db->beginTransaction();
            $Post_model->update($id, $params);
            if (isset($_POST['deleteImage'])) {
                foreach ($_POST['deleteImage'] as $img) {
                    $Post_model->deleteImage(h($img));
                }
            }
            FileUploadHelper::handleFileUpload($_FILES, $id, $Post_model);
            $post_tags = $Post_model->getTagsOfPost($id);
            foreach ($post_tags as $post_tag) {
                $Post_model->deletePostTag($post_tag['id']);
            }
            for ($i = 0; $i < count($_POST['tags']); $i++) {
                $Post_model->insertTag($id, $_POST['tags'][$i]);
            }
            $db->commit();

            $data = [];
            $post = $Post_model->getPost($id);
            $data['post'] = $post;
            $data['tags'] = $tags;
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message("success", "200", "Updated!")->view('post/edit', $data);
        } catch (PDOException $e) {
            $db->rollBack();
            $this->message('error', '500', 'Database error. Update failed!')->view('post/edit');
            return header('location:/posts');
        }
    }

    public function delete($id)
    {
        $csrf = new Csrf();
        $current_user = AuthHelper::getUserId();
        $Post_model = new Post();

        $csrf->verify();
        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        $post = $Post_model->getPost($id);
        if (empty($post) || ($post["OWNER"] != $current_user)) {
            return header('Location:/posts');
        }

        $db = $Post_model->db->database;
        try {
            $db->beginTransaction();
            $Post_model->deletePost($id);
            $db->commit();
            $this->message("success", "200", "Post is deleted");
            return header('location:/posts');
        } catch (PDOException $e) {
            $db->rollBack();
            $this->message('error', '500', 'Database error. Delete failed!');
            return header('location:/posts');
        }
    }

    public function show($id)
    {

        $csrf = new Csrf();
        $Post_model = new Post();

        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }
        $current_user = AuthHelper::getUserId();
        $post = $Post_model->getPost($id);
        if (empty($post) || ($post["OWNER"] !== $current_user)) {
            return header('Location:/posts');
        }
        $data['post'] = $post;
        $data['csrf_token'] = $csrf->generateToken();
        return $this->view('post/show', $data);
    }
}