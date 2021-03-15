<?php
require_once __DIR__ . '/../Models/Tag.php';
require_once 'BaseController.php';
include_once __DIR__ . '/../Helper/InputHelper.php';
include_once __DIR__ . '/../Helper/Csrf.php';


class TagController extends BaseController
{
    function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        $csrf = new Csrf();
        $data['csrf_token'] = $csrf->generateToken();

        return $this->view('tag/create', $data);
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

        $tag = new Tag();
        $result = $tag->getById($id);
        $data = [
            'tag' =>
                [
                    'id' => $result['id'],
                    'name' => $result['NAME'],
                    'description' => $result['description'],
                ]
        ];
        $this->view('tag/edit', $data);
    }

    public function store()
    {
        $csrf = new Csrf();
        $csrf->verify();

        if (empty($_POST['name'])) {
            $this->message('error', '406','Tag name is required!')->view('tag/create');;
        } else {
            if (!isset($_POST['description'])) {
                $_POST['description'] = '';
            }
            $tag = new Tag();
            try {
                $params['name'] = InputHelper::str($_POST['name']);
                $params['description'] = InputHelper::str($_POST['description']);
            } catch (Exception $e) {
                $this->message('error', $e->getCode(), $e->getMessage());
                return header('location:/posts');
            }

            if ($tag->create($params)) {
                return $this->message('success','201','Created!')->view('tag/create');
            }
            $this->message('error','400','Unexpected Error!')->view('tag/create');
        }
    }

    public function update($id)
    {
        $csrf = new Csrf();
        $csrf->verify();

        try {
            $params['owner'] = InputHelper::int($id);
        } catch (Exception $e) {
            $this->message('error', $e->getCode(), $e->getMessage());
            return header('location:/posts');
        }

        if (empty($_POST['name'])) {
            return $this->message('error','406','Tag name is required!')->view('tag/create');
        } else {
            if (!isset($_POST['description'])) {
                $_POST['description'] = '';
            }
            $tag = new Tag();
            $result = $tag->getById($id);

            $params['id'] = $result['id'];
            try {
                $params['name'] = InputHelper::str($_POST['name']);
                $params['description'] = InputHelper::str($_POST['description']);
            } catch (Exception $e) {
                $this->message('error', '400', 'Invalid input!');
                return header('location:/posts');
            }
            $result = $tag->update($params);
            if ($result) {
                $data = [
                    'tag' =>
                        [
                            'id' => $result['id'],
                            'name' => $result['NAME'],
                            'description' => $result['description'],
                        ]
                ];

                return $this->message('success','200','Updated!')->view('tag/edit', $data);
            }

            return $this->message('error','400','Unexpected Error!')->view('tag/edit');
        }
    }
}