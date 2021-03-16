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
        $data['tag'] = $tag->getById($id);
        return $this->view('tag/edit', $data);
    }

    public function store()
    {
        $csrf = new Csrf();
        $csrf->verify();
        $Tag_model = new Tag();
        $params = [];
        try {
            $params['name'] = InputHelper::str($_POST['name']);
            $params['description'] = InputHelper::str($_POST['description']);
        } catch (Exception $e) {
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', $e->getCode(), $e->getMessage())->view('tag/create');
        }

        $db = $Tag_model->db->database;
        try {
            $db->beginTransaction();
            $Tag_model->create($params);
            $db->commit();
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('success', '201', 'Created!')->view('tag/create');
        } catch (PDOException $e) {
            $db->rollBack();
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', '500', $e->getMessage())->view('tag/create');
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

        $Tag_model = new Tag();
        $result = $Tag_model->getById($id);

        $params['id'] = $result['id'];
        try {
            $params['name'] = InputHelper::str($_POST['name']);
            $params['description'] = InputHelper::str($_POST['description']);
        } catch (Exception $e) {
            $data['tag'] = $Tag_model->getById($id);
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', '400', 'Invalid input!')->view('tag/edit', $data);
        }

        $db = $Tag_model->db->database;
        try {
            $db->beginTransaction();
            $data['tag'] = $Tag_model->update($params);
            $db->commit();
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('success', '200', 'Updated!')->view('tag/edit', $data);

        } catch (PDOException $e) {
            $db->rollBack();
            $data['csrf_token'] = $csrf->generateToken();
            return $this->message('error', '500', $e->getMessage())->view('tag/edit');
        }
    }
}
