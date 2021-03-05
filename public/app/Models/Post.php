<?php


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
        $this->db>bind(':owner', $owner);
        $posts = $this->db->all();
        return $posts ? $posts : null;
    }
}