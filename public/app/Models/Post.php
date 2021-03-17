<?php
require_once 'Model.php';
require_once 'Tag.php';

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

        if ($post) {
            $post['tags'] = $this->getTagsOfPost($post['id']);
            $post['images'] = $this->getImagesOfPost($post['id']);
            return $post;
        }

        return null;
    }

    public function getPostByOwner($owner) {
        $this->db->query("SELECT * FROM posts WHERE owner = :owner AND deleted_at IS NULL 
                                ORDER BY created_at DESC");
        $this->db->bind(':owner', $owner, null);
        $posts = $this->db->all();
        if ($posts) {
            for ($i = 0; $i < count($posts); $i++) {
                $posts[$i]['tags'] = $this->getTagsOfPost($posts[$i]['id']);
                $posts[$i]['images'] = $this->getImagesOfPost($posts[$i]['id']);
            }
            return $posts;
        }
        return null;
    }

    public function getTagsOfPost($post_id) {
        $this->db->query("SELECT tags.id, tags.NAME as name FROM `posts` JOIN tags, post_tag 
                                WHERE posts.id = post_tag.post_id AND post_tag.tag_id = tags.id 
                                  AND posts.id = :post_id");
        $this->db->bind(':post_id', $post_id, null);
        $tags = $this->db->all();
        return $tags ? $tags : null;
    }

    public function getImagesOfPost($post_id) {
        $this->db->query("SELECT images.id, images.url FROM `posts` JOIN images 
                                    WHERE posts.id = images.post_id AND posts.id = :post_id 
                                      AND images.deleted_at IS NULL");
        $this->db->bind(':post_id', $post_id, null);
        $images = $this->db->all();
        return $images ? $images : null;
    }

    public function getImagesById($id) {
        $this->db->query("SELECT id, url FROM images WHERE id = :id");
        $this->db->bind(':id', $id, null);
        $images = $this->db->first();
        return $images ? $images : null;
    }

    public function update($id, $params) {
        $this->db->query("UPDATE posts SET title = :title, content = :content WHERE id = :id");
        $this->db->bind(':id', $id, null);
        $this->db->bind(':title', $params['title'], null);
        $this->db->bind(':content', $params['content'], null);
        if(!$this->db->execute()) {
            throw new PDOException("Update post failed!");
        };
    }

    public function create($params): ?int {
        $this->db->query("INSERT INTO posts (OWNER, title, content) VALUES (:owner, :title, :content)");
        $this->db->bind(':owner', $params['owner'], null);
        $this->db->bind(':title', $params['title'], null);
        $this->db->bind(':content', $params['content'], null);
        if ($this->db->execute()) {
            return $this->db->lastInsertedId();
        }
        throw new PDOException("Create failed!");
    }

    public function insertImage($post_id, $image) {
        $this->db->query("INSERT INTO images (post_id, url) VALUES (:post_id, :url)");
        $this->db->bind(':post_id', $post_id, null);
        $this->db->bind(':url', $image, null);
        if (!$this->db->execute()) {
            throw new PDOException("Insert image failed!");
        };
    }

    public function insertTag($id, $tag) {
        $this->db->query("INSERT INTO post_tag (post_id, tag_id) VALUES (:post_id, :tag_id)");
        $this->db->bind(':post_id', $id, null);
        $this->db->bind(':tag_id', $tag, null);
        if (!$this->db->execute()) {
            throw new PDOException("Insert tags failed!");
        };
    }

    public function deletePostTag($post_id, $tag_id) {
        $this->db->query("DELETE FROM post_tag WHERE post_id = :post_id AND tag_id = :tag_id");
        $this->db->bind(':post_id', $post_id, null);
        $this->db->bind(':tag_id', $tag_id, null);
        if (!$this->db->execute()) {
            throw new PDOException("Delete tags failed!");
        };
    }

    public function deleteImage($id) {
        $this->db->query("DELETE FROM images WHERE id = :id");
        $this->db->bind(':id', $id, null);
        if(!$this->db->execute()) {
            throw new PDOException("Delete image failed");
        };
    }

    public function deletePost($id) {
        $this->db->query("DELETE FROM images WHERE post_id = :id ");
        $this->db->bind(':id', $id, null);
        if(!$this->db->execute()) {
            throw new PDOException("An error occurred when remove image, Delete post failed!");
        };

        $this->db->query("DELETE FROM post_tag WHERE post_id = :id");
        $this->db->bind(':id', $id, null);
        if(!$this->db->execute()) {
            throw new PDOException("An error occurred when remove tag, Delete post failed!");
        };

        $this->db->query("DELETE FROM posts WHERE id = :id");
        $this->db->bind(':id', $id, null);
        if (!$this->db->execute()) {
            throw new PDOException("Delete post failed!");
        };
    }
}