<?php
include_once __DIR__.'/../Models/Post.php';
include_once __DIR__.'/../Models/Tag.php';
include_once __DIR__.'/../Helper/AuthHelper.php';
include_once 'BaseController.php';

class PostController extends BaseController
{
    public function index() {
        $owner_id = AuthHelper::getUserId();
        $Post_model = new Post();
        $posts = $Post_model->getPostByOwner($owner_id);
        $Tag_model = new Tag();
        $tags = $Tag_model->getAll();

        // very badddddd code
        for ($i = 0; $i < count($posts); $i++) {
            $post_tags = $Post_model->getTagsOfPost($posts[$i]['id']);
            $posts[$i]['tags'] = [];
            foreach ($post_tags as $post_tag) {
                foreach ($tags as $tag) {
                    if ($post_tag['tag_id'] == $tag['id']) {
                        array_push($posts[$i]['tags'], $tag['NAME']);
                    }
                }
            }
        }

        $data['posts'] = $posts;
        $this->view('post/index', $message = [],  $data);
    }

    public function create() {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;
        $this->view('post/create', $message = [], $data);
    }

    public function store() {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        if ($_POST['title'] == '' || $_POST['content'] == '') {
            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'Title and Content is required!',
            ];
            return $this->view('post/create', $message, $data);
        }

        if (!($_FILES["images"]["error"] > 0)) {
            print_r($_FILES["images"]["error"]);
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            for ($i = 0; $i< count($_FILES['images']['name']); $i++) {
                $name = basename($_FILES['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $message = [
                        'type' => 'error',
                        'status' => '400',
                        'message' => 'File not allowed!',
                    ];
                    return $this->view('post/create', $message, $data);
                }
            }
        }

        $owner_id = AuthHelper::getUserId();
        $params['owner'] = $owner_id;
        $params['title'] = $_POST['title'];
        $params['content'] = $_POST['content'];
        $Post_model = new Post();
        $post = $Post_model->create($params);

        if (!($_FILES["images"]["error"] > 0)) {
            $upload_dir = '/images';
            for ($i = 0; $i< count($_FILES['images']['tmp_name']); $i++) {
                $tmp_name = $_FILES['images']['tmp_name'][$i];
                $name = basename($_FILES['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $file_name = md5(time().$name);
                $file_name = $file_name.'.'.$ext;
                move_uploaded_file($tmp_name, "$upload_dir/$file_name");
                $Post_model->insertImage($post, "$upload_dir/$file_name");
            }
        }


        for ($i = 0; $i < count($_POST['tag']); $i++) {
            $Post_model->insertTag($post, $_POST['tag'][$i]);
        }

        $message = [
            'type' => 'success',
            'status' => '200',
            'message' => 'Create!',
        ];
        return $this->view('post/create', $message, $data);

    }

    public function edit($id) {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        $Post_model = new Post();
        $current_user = AuthHelper::getUserId();
        $post = $Post_model->getPost($id);
        if (!empty($post) && $post["OWNER"] !== $current_user) {
            return $this->view('post/index', $message = [],  $data);
        }

        $post['tags'] = [];
        $post_tags = $Post_model->getTagsOfPost($post['id']);
        foreach ($post_tags as $post_tag) {
            foreach ($tags as $tag) {
                if ($post_tag['tag_id'] == $tag['id']) {
                    $_tag = [
                        'id' => $tag['id'],
                        'name' => $tag['NAME']
                    ];
                    array_push($post['tags'], $_tag);
                }
            }
        }

        $images = $Post_model->getImagesOfPost($post['id']);
        $post['images'] = $images;

        $data['post'] = $post;
        $this->view('post/edit', $message = [], $data);
    }

    public function update($id) {
        $Tag = new Tag();
        $tags = $Tag->getAll();
        $data['tags'] = $tags;

        if ($_POST['title'] == '' || $_POST['content'] == '') {
            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'Title and Content is required!',
            ];
            return $this->view('post/create', $message, $data);
        }

        if (!($_FILES["images"]["error"] > 0)) {
            print_r($_FILES["images"]["error"]);
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            for ($i = 0; $i< count($_FILES['images']['name']); $i++) {
                $name = basename($_FILES['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $message = [
                        'type' => 'error',
                        'status' => '400',
                        'message' => 'File not allowed!',
                    ];
                    return $this->view('post/create', $message, $data);
                }
            }
        }

        $owner_id = AuthHelper::getUserId();
        $params['owner'] = $owner_id;
        $params['title'] = $_POST['title'];
        $params['content'] = $_POST['content'];
        $Post_model = new Post();
        $post = $Post_model->update($id, $params);

        if (!($_FILES["images"]["error"] > 0)) {
            $upload_dir = '/images';
            for ($i = 0; $i< count($_FILES['images']['tmp_name']); $i++) {
                $tmp_name = $_FILES['images']['tmp_name'][$i];
                $name = basename($_FILES['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $file_name = md5(time().$name);
                $file_name = $file_name.'.'.$ext;
                move_uploaded_file($tmp_name, "$upload_dir/$file_name");
                $Post_model->insertImage($post, "$upload_dir/$file_name");
            }
        }


        for ($i = 0; $i < count($_POST['tag']); $i++) {
            $Post_model->insertTag($post, $_POST['tag'][$i]);
        }

        $message = [
            'type' => 'success',
            'status' => '200',
            'message' => 'Create!',
        ];
        return $this->view('post/create', $message, $data);
    }
}