<?php
require_once 'Model.php';

class Post extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'posts';
    }

    public function getAll() {
        $this->db->query("SELECT * FROM posts");
        $posts = $this->db->all();
        return $posts ? $posts : null;
    }

    public function getPost($id) {
        $this->db->query("SELECT * FROM posts WHERE id = :id");
        $this->db>bind(':id', $id);
        $post = $this->db->first();
        return $post ? $post : null;
    }

    public function getPostByOwner($owner) {
        $this->db->query("SELECT * FROM posts WHERE owner = :owner");
        $this->db->bind(':owner', $owner, null);
        $posts = $this->db->all();
        return $posts ? $posts : null;
    }

    public function create($params) {
        $this->db->query("INSERT INTO posts (OWNER, title, content) VALUES (:owner, :title, :content)");
        $this->db->bind(':owner', $params['owner'], null);
        $this->db->bind(':title', $params['title'], null);
        $this->db->bind(':content', $params['content'], null);
        $this->db->execute();
        return $this->db->lastInsertedId();
    }

    public function insertImage($id, $image) {
        $this->db->query("INSERT INTO images (post_id, url) VALUES (:post_id, :url)");
        $this->db->bind(':post_id', $id, null);
        $this->db->bind(':url', $image, null);
        return $this->db->execute();

    }
}