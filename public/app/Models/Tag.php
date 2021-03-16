<?php
require_once 'Model.php';

class Tag extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'tags';
    }

    public function getAll() {
        $this->db->query("SELECT * FROM tags ");
        $tags = $this->db->all();
        return $tags ? $tags : null;
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM tags where id = :id");
        $this->db->bind(':id', $id, null);
        $tag = $this->db->first();
        return $tag ? $tag : null;
    }


    public function create($params) {
        $this->db->query("INSERT INTO tags (NAME, description) VALUES (:name, :description)");
        $this->db->bind(':name', $params["name"], null);
        $this->db->bind(':description', $params["description"], null);
        if (!$this->db->execute()) {
            throw new PDOException("Create tag failed!");
        };
    }

    public function update($params) {
        $this->db->query("UPDATE tags SET NAME = :name, description = :description, updated_at = :updated_at
                WHERE id = :id");
        $this->db->bind(':id', $params["id"], null);
        $this->db->bind(':name', $params["name"], null);
        $this->db->bind(':updated_at', date("Y-m-d H:i:s"), null);
        $this->db->bind(':description', $params["description"], null);
        if ($this->db->execute()) {
            return $tag = $this->getById($params["id"]);
        } else {
            throw new PDOException("Update tag failed");
        }
    }

}