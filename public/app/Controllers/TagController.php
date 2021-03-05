<?php
require_once __DIR__ . '/../Models/Tag.php';
require_once 'BaseController.php';

class TagController extends BaseController
{
    public function create()
    {
        return $this->view('tag/create');
    }

    public function edit($id)
    {
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
        $this->view('tag/edit', $message = [], $data);
    }

    public function store()
    {
        if (!isset($_POST['name'])) {
            $message = [
                'type' => 'error',
                'status' => '406',
                'message' => 'Create Fail!'
            ];

            $this->view('tag/create', $message);;
        } else {
            if (!isset($_POST['description'])) {
                $_POST['description'] = '';
            }
            $tag = new Tag();
            $params['name'] = $_POST['name'];
            $params['description'] = $_POST['description'];

            if ($tag->create($params)) {
                $message = [
                    'type' => 'success',
                    'status' => '201',
                    'message' => 'Created!'
                ];

                $this->view('tag/create', $message);
            }

            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'Unexpected Error!'
            ];

            $this->view('tag/create', $message);
        }
    }

    public function update($id)
    {
        if (!isset($_POST['name'])) {
            $message = [
                'type' => 'error',
                'status' => '406',
                'message' => 'Create Fail!'
            ];

            $this->view('tag/create', $message);
        } else {
            if (!isset($_POST['description'])) {
                $_POST['description'] = '';
            }
            $tag = new Tag();
            $result = $tag->getById($id);

            $params['id'] = $result['id'];
            $params['name'] = $_POST['name'];
            $params['description'] = $_POST['description'];
            $result = $tag->update($params);
            if ($result) {
                $message = [
                    'type' => 'success',
                    'status' => '200',
                    'message' => 'Updated!'
                ];

                $data = [
                    'tag' =>
                        [
                            'id' => $result['id'],
                            'name' => $result['NAME'],
                            'description' => $result['description'],
                        ]
                ];

                $this->view('tag/edit', $message, $data);
            }

            $message = [
                'type' => 'error',
                'status' => '400',
                'message' => 'Unexpected Error!'
            ];

            $this->view('tag/edit', $message);
        }
    }
}