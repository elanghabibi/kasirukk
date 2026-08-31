<?php

class Model
{
    protected $table;
    protected $fillable = [];
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all()
    {
        $query = "SELECT * FROM $this->table";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function store($data)
    {
        $columns = implode(",", $this->fillable);
        $params = ":" . implode(", :", $this->fillable);

        $query = "INSERT INTO $this->table ($columns, created_at) VALUES ($params, NOW())";
        $this->db->query($query);

        foreach ($data as $key => $value) {
            $this->db->bind($key, $value);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function update($data, $id)
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $fields['id'] = $id;
        if (empty($fields)) {
            return 0;
        }

        $set = [];
        foreach ($fields as $key => $value) {
            $set[] = "$key = :$key";
        }

        $setClause = implode(", ", $set);

        $query = "UPDATE $this->table SET $setClause, updated_at = NOW() WHERE id=:id";

        $this->db->query($query);

        foreach ($fields as $key => $value) {
            $this->db->bind($key, $value);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function destroy($id)
    {
        $query = "DELETE FROM $this->table WHERE id=:id";
        $this->db->query($query);
        $this->db->bind("id", $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function where($column, $value)
    {
        $query = "SELECT * FROM $this->table WHERE $column=:value";
        $this->db->query($query);
        $this->db->bind("value", $value);

        return $this->db->resultSet();
    }

    public function find($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $this->db->query($query);
        $this->db->bind("id", $id);

        return $this->db->single();
    }

    public function findOrFail($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $this->db->query($query);
        $this->db->bind("id", $id);

        $result = $this->db->single();

        if (!$result) {
            http_response_code(404);
            exit('404 Not Found');
        }

        return $result;
    }

    public function exists($column, $value)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$column} = :value";
        $this->db->query($query);
        $this->db->bind('value', $value);

        return $this->db->single()['total'] > 0;
    }


}


?>