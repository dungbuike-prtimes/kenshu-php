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
        $this->view('tag/edit', $data);
    }

    public function store()
    {
        if (empty($_POST['name'])) {

            $this->flash('error', '406','Tag name is required!')->view('tag/create');;
        } else {
            if (!isset($_POST['description'])) {
                $_POST['description'] = '';
            }
            $tag = new Tag();
            $params['name'] = $_POST['name'];
            $params['description'] = $_POST['description'];

            if ($tag->create($params)) {
                return $this->flash('success','201','Created!')->view('tag/create');
            }

            $this->flash('error','400','Unexpected Error!')->view('tag/create');
        }
    }

    public function update($id)
    {
        if (empty($_POST['name'])) {
            return $this->flash('error','406','Tag name is required!')->view('tag/create');
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
                $data = [
                    'tag' =>
                        [
                            'id' => $result['id'],
                            'name' => $result['NAME'],
                            'description' => $result['description'],
                        ]
                ];

                return $this->flash('success','200','Updated!')->view('tag/edit', $data);
            }

            return $this->flash('error','400','Unexpected Error!')->view('tag/edit');
        }
    }
}