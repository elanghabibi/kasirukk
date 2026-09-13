<?php

abstract class Model
{
    protected $table;
    protected $fillable = [];

    // Aktifkan pada model yang ingin menggunakan soft delete
    protected $softDelete = false;

    protected $db;
    protected $select = '*';

    protected $joins = [];
    protected $where = [];
    protected $bindings = [];

    public function __construct()
    {
        $this->db = new Database();
    }

    /*
    /--------------------------------------
    / JOIN
    /-----------------------------
    */

    public function select($columns)
    {
        $this->select = $columns;

        return $this;
    }

    public function join($table, $condition, $type = 'LEFT')
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$condition}";

        return $this;
    }

    protected function getJoins()
    {
        return empty($this->joins)
            ? ''
            : ' ' . implode(' ', $this->joins);
    }

    public function get()
    {
        $query = "SELECT {$this->select}
              FROM {$this->table}";

        $query .= $this->getJoins();

        if (!empty($this->where)) {
            $query .= " WHERE " . implode(' AND ', $this->where);
        }

        $query .= " ORDER BY {$this->table}.created_at DESC";

        $this->db->query($query);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
    }

    protected function resetQuery()
    {
        $this->select = '*';
        $this->where = [];
        $this->joins = [];
        $this->bindings = [];
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
        $query = "SELECT {$this->select}
              FROM {$this->table}";

        $query .= $this->getJoins();

        $query .= " WHERE {$this->table}.id = :id";

        if ($this->softDelete) {
            $query .= " AND {$this->table}.deleted_at IS NULL";
        }

        $this->db->query($query);
        $this->db->bind(':id', $id);

        $result = $this->db->single();

        $this->resetQuery();

        return $result;
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
            return $this;
        }

        $conditions = [];

        foreach ($columns as $index => $column) {
            $placeholder = ":keyword{$index}";

            $conditions[] = "{$this->table}.{$column} LIKE {$placeholder}";

            $this->bindings[$placeholder] = "%{$keyword}%";
        }

        $where = '(' . implode(' OR ', $conditions) . ')';

        $this->where[] = $where;

        return $this;
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
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = "{$column} {$operator} :where_" . count($this->bindings);

        $this->bindings[':where_' . count($this->bindings)] = $value;

        return $this;
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
    public function orderBy($column, $direction = 'ASC', $trashed = 'active')
    {
        $allowedDirection = ['ASC', 'DESC'];
        $direction = strtoupper($direction);

        if (!in_array($direction, $allowedDirection)) {
            $direction = 'ASC';
        }

        $query = "SELECT {$this->select}
              FROM {$this->table}";

        $query .= $this->getJoins();

        // Soft delete
        if ($this->softDelete) {

            if ($trashed === 'active') {
                $query .= " WHERE {$this->table}.deleted_at IS NULL";
            } elseif ($trashed === 'onlyTrashed') {
                $query .= " WHERE {$this->table}.deleted_at IS NOT NULL";
            } elseif ($trashed === 'withTrashed') {
                // Tidak menambahkan WHERE deleted_at
            }
        }

        $query .= " ORDER BY {$column} {$direction}";

        $this->db->query($query);

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
    }


    /**
     * Data terbaru.
     */
    public function latest($trashed = 'active')
    {
        $query = "SELECT {$this->select}
              FROM {$this->table}";

        $query .= $this->getJoins();

        // Soft delete
        if ($this->softDelete) {

            if ($trashed === 'active') {
                $this->where[] = "{$this->table}.deleted_at IS NULL";
            } elseif ($trashed === 'onlyTrashed') {
                $this->where[] = "{$this->table}.deleted_at IS NOT NULL";
            }

            // withTrashed → tidak menambahkan kondisi deleted_at
        }

        if (!empty($this->where)) {
            $query .= " WHERE " . implode(' AND ', $this->where);
        }

        $query .= " ORDER BY {$this->table}.created_at DESC";

        $this->db->query($query);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
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

    public function paginate($perPage = 10, $page = 1, $trashed = null)
    {
        $perPage = max(1, (int) $perPage);
        $page = max(1, (int) $page);

        // Jika parameter tidak diberikan,
        // gunakan mode soft delete dari model.
        $trashed = $trashed ?? "active";

        $offset = ($page - 1) * $perPage;

        /*
    |--------------------------------------------------------------------------
    | Filter soft delete
    |--------------------------------------------------------------------------
    */

        if ($this->softDelete) {

            if ($trashed === 'active') {
                $this->where[] = "{$this->table}.deleted_at IS NULL";
            } elseif ($trashed === 'onlyTrashed') {
                $this->where[] = "{$this->table}.deleted_at IS NOT NULL";
            }

            // withTrashed tidak menambahkan filter
        }

        /*
    |--------------------------------------------------------------------------
    | Query dasar
    |--------------------------------------------------------------------------
    */

        $fromQuery = "FROM {$this->table}";

        $fromQuery .= $this->getJoins();

        $whereQuery = '';

        if (!empty($this->where)) {
            $whereQuery = " WHERE " . implode(' AND ', $this->where);
        }

        /*
    |--------------------------------------------------------------------------
    | Query total data
    |--------------------------------------------------------------------------
    */

        $countQuery = "SELECT COUNT(*) AS total
                   {$fromQuery}
                   {$whereQuery}";

        $this->db->query($countQuery);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        $countResult = $this->db->single();

        $total = (int) ($countResult['total'] ?? 0);

        /*
    |--------------------------------------------------------------------------
    | Query data pagination
    |--------------------------------------------------------------------------
    */

        $dataQuery = "SELECT {$this->select}
                  {$fromQuery}
                  {$whereQuery}
                  ORDER BY {$this->table}.created_at DESC
                  LIMIT {$perPage} OFFSET {$offset}";

        $this->db->query($dataQuery);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        $data = $this->db->resultSet();

        /*
    |--------------------------------------------------------------------------
    | Informasi pagination
    |--------------------------------------------------------------------------
    */

        $totalPages = $total > 0
            ? (int) ceil($total / $perPage)
            : 0;

        $result = [
            'data' => $data,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'from' => $total > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $total),
            'hasNextPage' => $page < $totalPages,
            'hasPreviousPage' => $page > 1,
        ];

        $this->resetQuery();

        return $result;
    }
}
