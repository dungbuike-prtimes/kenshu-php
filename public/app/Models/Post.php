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
        $this->db->query("SELECT * FROM posts WHERE deleted_at IS NULL");
        $posts = $this->db->all();
        return $posts ? $posts : null;
    }

    public function getPost($id) {
        $this->db->query("SELECT * FROM posts WHERE id = :id AND deleted_at IS NULL");
        $this->db->bind(':id', $id, null);
        $post = $this->db->first();
        return $post ? $post : null;
    }

    public function getPostByOwner($owner) {
        $this->db->query("SELECT * FROM posts WHERE owner = :owner AND deleted_at IS NULL");
        $this->db->bind(':owner', $owner, null);
        $posts = $this->db->all();
        return $posts ? $posts : null;
    }

    public function getTagsOfPost($post_id) {
        $this->db->query("SELECT * FROM post_tag WHERE post_id = :post_id AND deleted_at IS NULL");
        $this->db->bind(':post_id', $post_id, null);
        $tags = $this->db->all();
        return $tags ? $tags : null;
    }

    public function getImagesOfPost($post_id) {
        $this->db->query("SELECT * FROM images WHERE post_id = :post_id AND deleted_at IS NULL");
        $this->db->bind(':post_id', $post_id, null);
        $images = $this->db->all();
        return $images ? $images : null;
    }

    public function update($id, $params) {
        $this->db->query("UPDATE TABLE posts SET title = :title, content = :content WHERE id = :id");
        $this->db->bind(':id', $id, null);
        $this->db->bind(':title', $params['title'], null);
        $this->db->bind(':content', $params['content'], null);
        return $this->db->execute();
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

    public function insertTag($id, $tag) {
        $this->db->query("INSERT INTO post_tag (post_id, tag_id) VALUES (:post_id, :tag_id)");
        $this->db->bind(':post_id', $id, null);
        $this->db->bind(':tag_id', $tag, null);
        return $this->db->execute();
    }
}