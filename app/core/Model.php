<?php

abstract class Model
{
    protected $table;
    protected $fillable = [];

    // Aktifkan pada model yang ingin menggunakan soft delete
    protected $softDelete = false;

    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    /*
    |--------------------------------------------------------------------------
    | BASIC
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil semua data aktif.
     */
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";

        if ($this->softDelete) {
            $query .= " WHERE deleted_at IS NULL";
        }

        $this->db->query($query);

        return $this->db->resultSet();
    }


    /**
     * Mengambil satu data berdasarkan ID.
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);
        $this->db->bind("id", $id);

        return $this->db->single();
    }


    /**
     * Find atau 404.
     */
    public function findOrFail($id)
    {
        $result = $this->find($id);

        if (!$result) {
            http_response_code(404);
            exit('404 Not Found');
        }

        return $result;
    }

    public function search($keyword, $columns = [])
    {
        if (empty($columns)) {
            return $this->all();
        }

        $conditions = [];

        foreach ($columns as $column) {
            $conditions[] = "{$column} LIKE :keyword";
        }

        $where = implode(" OR ", $conditions);

        $query = "SELECT *
                FROM {$this->table}
                WHERE ({$where})";

        // Hanya tampilkan data aktif jika menggunakan soft delete
        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $query .= " ORDER BY id DESC";

        $this->db->query($query);
        $this->db->bind(":keyword", "%" . $keyword . "%");

        return $this->db->resultSet();
    }


    /*
    |--------------------------------------------------------------------------
    | WHERE
    |--------------------------------------------------------------------------
    */

    /**
     * WHERE sederhana.
     *
     * where('role', 'admin')
     */
    public function where($column, $value)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE {$column} = :value";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);
        $this->db->bind("value", $value);

        return $this->db->resultSet();
    }


    /**
     * WHERE + FIRST.
     *
     * firstWhere('email', 'test@gmail.com')
     */
    public function firstWhere($column, $value)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE {$column} = :value";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $query .= " LIMIT 1";

        $this->db->query($query);
        $this->db->bind("value", $value);

        return $this->db->single();
    }


    /**
     * Mengambil satu data pertama.
     */
    public function first()
    {
        $query = "SELECT * FROM {$this->table}";

        if ($this->softDelete) {
            $query .= " WHERE deleted_at IS NULL";
        }

        $query .= " LIMIT 1";

        $this->db->query($query);

        return $this->db->single();
    }


    /**
     * First atau 404.
     */
    public function firstOrFail()
    {
        $result = $this->first();

        if (!$result) {
            http_response_code(404);
            exit('404 Not Found');
        }

        return $result;
    }

    // Hitung total dari kolom tertentu bulan ini
    public function sumThisMonth($column, $dateColumn = 'created_at')
{
    $query = "SELECT SUM({$column}) AS total
              FROM {$this->table}
              WHERE {$column} IS NOT NULL
              AND {$dateColumn} >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
              AND {$dateColumn} < DATE_FORMAT(
                  CURDATE() + INTERVAL 1 MONTH,
                  '%Y-%m-01'
              )";

    if ($this->softDelete) {
        $query .= " AND deleted_at IS NULL";
    }

    $this->db->query($query);

    $result = $this->db->single();

    return (float) ($result['total'] ?? 0);
}


    /*
    |--------------------------------------------------------------------------
    | COUNT & EXISTS
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung jumlah data aktif.
     *
     * count()
     * count('role', 'admin')
     */
    public function count($column = null, $value = null)
    {
        $query = "SELECT COUNT(*) AS total
                  FROM {$this->table}";

        $conditions = [];

        if ($this->softDelete) {
            $conditions[] = "deleted_at IS NULL";
        }

        if ($column !== null) {
            $conditions[] = "{$column} = :value";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $this->db->query($query);

        if ($column !== null) {
            $this->db->bind("value", $value);
        }

        $result = $this->db->single();

        return (int) $result['total'];
    }

    public function countThisMonth($column = 'created_at')
    {
        $query = "SELECT COUNT(*) AS total
                FROM {$this->table}
                WHERE {$column} >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                AND {$column} < DATE_FORMAT(
                    CURDATE() + INTERVAL 1 MONTH,
                    '%Y-%m-01'
                )";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);

        $result = $this->db->single();

        return (int) $result['total'];
    }

    /**
     * Mengecek apakah data ada.
     *
     * exists('email', 'test@gmail.com')
     */
    public function exists($column, $value)
    {
        $query = "SELECT COUNT(*) AS total
                  FROM {$this->table}
                  WHERE {$column} = :value";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);
        $this->db->bind("value", $value);

        $result = $this->db->single();

        return (int) $result['total'] > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Membuat data baru.
     *
     * store([
     *     'name' => 'Es Kopi',
     *     'price' => 10000
     * ]);
     */
    public function store($data)
    {
        $fields = array_intersect_key(
            $data,
            array_flip($this->fillable)
        );

        if (empty($fields)) {
            return 0;
        }

        $columns = implode(", ", array_keys($fields));
        $params = ":" . implode(", :", array_keys($fields));

        $query = "INSERT INTO {$this->table}
                  ({$columns}, created_at)
                  VALUES ({$params}, NOW())";

        $this->db->query($query);

        foreach ($fields as $key => $value) {
            $this->db->bind($key, $value);
        }

        $this->db->execute();

        return $this->db->rowCount();
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update berdasarkan ID.
     */
    public function update($data, $id)
    {
        $fields = array_intersect_key(
            $data,
            array_flip($this->fillable)
        );

        if (empty($fields)) {
            return 0;
        }

        $set = [];

        foreach ($fields as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }

        $setClause = implode(", ", $set);

        $query = "UPDATE {$this->table}
                  SET {$setClause}, updated_at = NOW()
                  WHERE id = :id";

        $this->db->query($query);

        foreach ($fields as $key => $value) {
            $this->db->bind($key, $value);
        }

        $this->db->bind("id", $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Hard delete.
     */
    public function destroy($id)
    {
        $query = "DELETE FROM {$this->table}
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind("id", $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /*
    |--------------------------------------------------------------------------
    | SOFT DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Soft delete.
     */
    public function softDelete($id)
    {
        if (!$this->softDelete) {
            return $this->destroy($id);
        }

        $query = "UPDATE {$this->table}
                  SET deleted_at = NOW()
                  WHERE id = :id
                  AND deleted_at IS NULL";

        $this->db->query($query);
        $this->db->bind("id", $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /**
     * Restore data yang sudah di-soft delete.
     */
    public function restore($id)
    {
        if (!$this->softDelete) {
            return 0;
        }

        $query = "UPDATE {$this->table}
                  SET deleted_at = NULL,
                      updated_at = NOW()
                  WHERE id = :id
                  AND deleted_at IS NOT NULL";

        $this->db->query($query);
        $this->db->bind("id", $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /**
     * Mengambil data yang sudah di-soft delete saja.
     */
    public function onlyTrashed()
    {
        if (!$this->softDelete) {
            return [];
        }

        $query = "SELECT * FROM {$this->table}
                  WHERE deleted_at IS NOT NULL";

        $this->db->query($query);

        return $this->db->resultSet();
    }


    /**
     * Mengambil semua data termasuk yang di-soft delete.
     */
    public function withTrashed()
    {
        $query = "SELECT * FROM {$this->table}";

        $this->db->query($query);

        return $this->db->resultSet();
    }


    /**
     * Mengecek apakah data sedang di-soft delete.
     */
    public function trashed($id)
    {
        if (!$this->softDelete) {
            return false;
        }

        $query = "SELECT COUNT(*) AS total
                  FROM {$this->table}
                  WHERE id = :id
                  AND deleted_at IS NOT NULL";

        $this->db->query($query);
        $this->db->bind("id", $id);

        $result = $this->db->single();

        return (int) $result['total'] > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | WHERE IN
    |--------------------------------------------------------------------------
    */

    /**
     * WHERE IN.
     *
     * whereIn('id', [1, 2, 3])
     */
    public function whereIn($column, array $values)
    {
        if (empty($values)) {
            return [];
        }

        $placeholders = [];

        foreach ($values as $index => $value) {
            $placeholders[] = ":value{$index}";
        }

        $query = "SELECT * FROM {$this->table}
                  WHERE {$column} IN (" .
                  implode(", ", $placeholders) .
                  ")";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);

        foreach ($values as $index => $value) {
            $this->db->bind("value{$index}", $value);
        }

        return $this->db->resultSet();
    }


    /*
    |--------------------------------------------------------------------------
    | NULL
    |--------------------------------------------------------------------------
    */

    /**
     * WHERE column IS NULL.
     */
    public function whereNull($column)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE {$column} IS NULL";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);

        return $this->db->resultSet();
    }


    /**
     * WHERE column IS NOT NULL.
     */
    public function whereNotNull($column)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE {$column} IS NOT NULL";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $this->db->query($query);

        return $this->db->resultSet();
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

    /**
     * Mengurutkan data.
     *
     * orderBy('name', 'ASC')
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $allowedDirection = ['ASC', 'DESC'];

        $direction = strtoupper($direction);

        if (!in_array($direction, $allowedDirection)) {
            $direction = 'ASC';
        }

        $query = "SELECT * FROM {$this->table}";

        if ($this->softDelete) {
            $query .= " WHERE deleted_at IS NULL";
        }

        $query .= " ORDER BY {$column} {$direction}";

        $this->db->query($query);

        return $this->db->resultSet();
    }


    /**
     * Data terbaru.
     */
    public function latest($column = 'created_at')
    {
        return $this->orderBy($column, 'DESC');
    }


    /**
     * Data terlama.
     */
    public function oldest($column = 'created_at')
    {
        return $this->orderBy($column, 'ASC');
    }


    /*
    |--------------------------------------------------------------------------
    | LIMIT
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil sejumlah data.
     *
     * limit(10)
     */
    public function limit($limit)
    {
        $limit = (int) $limit;

        if ($limit <= 0) {
            return [];
        }

        $query = "SELECT * FROM {$this->table}";

        if ($this->softDelete) {
            $query .= " WHERE deleted_at IS NULL";
        }

        $query .= " LIMIT {$limit}";

        $this->db->query($query);

        return $this->db->resultSet();
    }
}