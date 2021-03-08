<?php
include_once __DIR__.'/../Models/Post.php';
include_once __DIR__.'/../Helper/AuthHelper.php';
include_once 'BaseController.php';

class PostController extends BaseController
{
    public function index() {
        $owner_id = AuthHelper::getUserId();
        $model = new Post();
        $posts = $model->getPostByOwner($owner_id);
        $data['posts'] = $posts;
        $this->view('post/index', $data);
    }

    public function create() {
        $this->view('post/create');
    }

    public function store() {
        if (!isset($_POST['title']) && !isset($_POST['content'])) {
            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'Title and Content is required!',
            ];
            return $this->view('post/create', $message, $data = []);
        }

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
                return $this->view('post/create', $message, $data = []);
            }
        }

        $owner_id = AuthHelper::getUserId();
        $params['owner'] = $owner_id;
        $params['title'] = $_POST['title'];
        $params['content'] = $_POST['content'];
        $model = new Post();
        $post = $model->create($params);

        $upload_dir = __DIR__.'/../../images';
        for ($i = 0; $i< count($_FILES['images']['tmp_name']); $i++) {
            $tmp_name = $_FILES['images']['tmp_name'][$i];
            $name = basename($_FILES['images']['name'][$i]);
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $file_name = md5(time().$name);
            $file_name = $file_name.'.'.$ext;
            move_uploaded_file($tmp_name, "$upload_dir/$file_name");
            $model->insertImage($post, "$upload_dir/$file_name");
        }

        $message = [
            'type' => 'success',
            'status' => '200',
            'message' => 'Create!',
        ];
        return $this->view('post/create', $message, $data = []);

    }
}