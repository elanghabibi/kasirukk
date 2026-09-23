<?php

abstract class Model
{
    protected $table;
    protected $fillable = [];

    // Soft delete
    protected $softDelete = false;
    protected $trashedMode = 'active';

    // Database
    protected $db;

    // Query Builder
    protected $select = '*';
    protected $joins = [];
    protected $where = [];
    protected $bindings = [];

    public function __construct()
    {
        $this->db = new Database();
    }


    /*
    |--------------------------------------------------------------------------
    | QUERY BUILDER
    |--------------------------------------------------------------------------
    */


    /**
     * SELECT
     *
     * select('users.*, roles.nama AS nama_role')
     */
    public function select($columns)
    {
        $this->select = $columns;

        return $this;
    }


    /**
     * JOIN
     *
     * join(
     *     'kategori',
     *     'kategori.id = menu.kategori_id'
     * )
     */
    public function join($table, $condition, $type = 'LEFT')
    {
        $allowedTypes = [
            'LEFT',
            'RIGHT',
            'INNER',
            'FULL'
        ];

        $type = strtoupper($type);

        if (!in_array($type, $allowedTypes)) {
            $type = 'LEFT';
        }

        $this->joins[] = "{$type} JOIN {$table} ON {$condition}";

        return $this;
    }


    protected function getJoins()
    {
        return empty($this->joins)
            ? ''
            : ' ' . implode(' ', $this->joins);
    }


    /**
     * WHERE
     *
     * where('role', 'admin')
     *
     * where('harga', '>', 10000)
     */
    public function where($column, $operator, $value = null)
    {
        // where('role', 'admin')
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $operator = strtoupper(trim($operator));

        $allowedOperators = [
            '=',
            '!=',
            '<>',
            '>',
            '<',
            '>=',
            '<=',
            'LIKE',
            'NOT LIKE'
        ];

        if (!in_array($operator, $allowedOperators)) {
            throw new InvalidArgumentException(
                "Operator WHERE tidak valid: {$operator}"
            );
        }

        $placeholder = ':where_' . count($this->bindings);

        $this->where[] =
            "{$this->table}.{$column} {$operator} {$placeholder}";

        $this->bindings[$placeholder] = $value;

        return $this;
    }


    /**
     * WHERE IN
     *
     * whereIn('id', [1, 2, 3])
     */
    public function whereIn($column, array $values)
    {
        if (empty($values)) {
            // Supaya tidak mengambil semua data.
            $this->where[] = '1 = 0';

            return $this;
        }

        $placeholders = [];

        foreach ($values as $index => $value) {
            $placeholder = ':where_in_' . count($this->bindings);

            $placeholders[] = $placeholder;

            $this->bindings[$placeholder] = $value;
        }

        $this->where[] =
            "{$this->table}.{$column} IN (" .
            implode(', ', $placeholders) .
            ")";

        return $this;
    }


    /**
     * WHERE NULL
     *
     * whereNull('deleted_at')
     */
    public function whereNull($column)
    {
        $this->where[] =
            "{$this->table}.{$column} IS NULL";

        return $this;
    }


    /**
     * WHERE NOT NULL
     *
     * whereNotNull('deleted_at')
     */
    public function whereNotNull($column)
    {
        $this->where[] =
            "{$this->table}.{$column} IS NOT NULL";

        return $this;
    }


    /**
     * SEARCH
     *
     * search('kopi', ['nama', 'email'])
     */
    public function search($keyword, $columns = [])
    {
        if (empty($columns)) {
            return $this;
        }

        $conditions = [];

        foreach ($columns as $index => $column) {
            $placeholder =
                ':keyword_' . count($this->bindings);

            $conditions[] =
                "{$this->table}.{$column} LIKE {$placeholder}";

            $this->bindings[$placeholder] =
                "%{$keyword}%";
        }

        $this->where[] =
            '(' . implode(' OR ', $conditions) . ')';

        return $this;
    }


    /*
    |--------------------------------------------------------------------------
    | SOFT DELETE
    |--------------------------------------------------------------------------
    */


    protected function getSoftDeleteCondition()
    {
        if (!$this->softDelete) {
            return '';
        }

        if ($this->trashedMode === 'active') {
            return "{$this->table}.deleted_at IS NULL";
        }

        if ($this->trashedMode === 'onlyTrashed') {
            return "{$this->table}.deleted_at IS NOT NULL";
        }

        // withTrashed
        return '';
    }


    public function onlyTrashed()
    {
        $this->trashedMode = 'onlyTrashed';

        return $this;
    }


    public function withTrashed()
    {
        $this->trashedMode = 'withTrashed';

        return $this;
    }


    protected function addSoftDeleteCondition()
    {
        $condition = $this->getSoftDeleteCondition();

        if ($condition) {
            $this->where[] = $condition;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EXECUTOR
    |--------------------------------------------------------------------------
    */


    /**
     * GET
     *
     * Mengambil banyak data.
     */
    public function get()
    {
        $this->addSoftDeleteCondition();

        $query = "SELECT {$this->select}
                  FROM {$this->table}";

        $query .= $this->getJoins();

        if (!empty($this->where)) {
            $query .= " WHERE " .
                implode(' AND ', $this->where);
        }

        // $query .=
        //     " ORDER BY {$this->table}.created_at DESC";

        $this->db->query($query);

        $this->bindParameters();

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
    }


    /**
     * FIRST
     *
     * Mengambil satu data pertama.
     */
    public function first()
    {
        $this->addSoftDeleteCondition();

        $query = "SELECT {$this->select}
                  FROM {$this->table}";

        $query .= $this->getJoins();

        if (!empty($this->where)) {
            $query .= " WHERE " .
                implode(' AND ', $this->where);
        }

        $query .= " LIMIT 1";

        $this->db->query($query);

        $this->bindParameters();

        $result = $this->db->single();

        $this->resetQuery();

        return $result;
    }


    /**
     * FIRST WHERE
     *
     * firstWhere('email', 'test@gmail.com')
     */
    public function firstWhere($column, $value)
    {
        return $this
            ->where($column, $value)
            ->first();
    }


    /**
     * FIRST OR FAIL
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


    /**
     * FIND
     *
     * find(10)
     *
     * Bisa digunakan:
     *
     * $menuModel
     *     ->withKategori()
     *     ->find($id);
     */
    public function find($id)
    {
        return $this
            ->where('id', $id)
            ->first();
    }


    /**
     * FIND OR FAIL
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


    /*
    |--------------------------------------------------------------------------
    | BASIC
    |--------------------------------------------------------------------------
    */


    /**
     * ALL
     *
     * Mengambil semua data aktif.
     */
    public function all()
    {
        return $this->get();
    }


    /**
     * LATEST
     */
    public function latest()
    {
        return $this->get();
    }


    /**
     * OLDEST
     */
    public function oldest($column = 'created_at')
    {
        $this->addSoftDeleteCondition();

        $query = "SELECT {$this->select}
                  FROM {$this->table}";

        $query .= $this->getJoins();

        if (!empty($this->where)) {
            $query .= " WHERE " .
                implode(' AND ', $this->where);
        }

        $query .=
            " ORDER BY {$this->table}.{$column} ASC";

        $this->db->query($query);

        $this->bindParameters();

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
    }


    /**
     * ORDER BY
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $allowedDirection = [
            'ASC',
            'DESC'
        ];

        $direction = strtoupper($direction);

        if (!in_array($direction, $allowedDirection)) {
            $direction = 'ASC';
        }

        $this->addSoftDeleteCondition();

        $query = "SELECT {$this->select}
                  FROM {$this->table}";

        $query .= $this->getJoins();

        if (!empty($this->where)) {
            $query .= " WHERE " .
                implode(' AND ', $this->where);
        }

        $query .=
            " ORDER BY {$this->table}.{$column} {$direction}";

        $this->db->query($query);

        $this->bindParameters();

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
    }


    /**
     * LIMIT
     */
    public function limit($limit)
    {
        $limit = max(1, (int) $limit);

        $this->addSoftDeleteCondition();

        $query = "SELECT {$this->select}
                  FROM {$this->table}";

        $query .= $this->getJoins();

        if (!empty($this->where)) {
            $query .= " WHERE " .
                implode(' AND ', $this->where);
        }

        $query .= " LIMIT {$limit}";

        $this->db->query($query);

        $this->bindParameters();

        $result = $this->db->resultSet();

        $this->resetQuery();

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */


    public function paginate(
        $perPage = 10,
        $page = 1
    ) {
        $perPage = max(1, (int) $perPage);
        $page = max(1, (int) $page);

        $offset = ($page - 1) * $perPage;

        $this->addSoftDeleteCondition();

        $fromQuery =
            "FROM {$this->table}" .
            $this->getJoins();

        $whereQuery = '';

        if (!empty($this->where)) {
            $whereQuery =
                " WHERE " .
                implode(' AND ', $this->where);
        }


        /*
        |--------------------------------------------------------------------------
        | COUNT
        |--------------------------------------------------------------------------
        */

        $countQuery =
            "SELECT COUNT(*) AS total
             {$fromQuery}
             {$whereQuery}";

        $this->db->query($countQuery);

        $this->bindParameters();

        $countResult = $this->db->single();

        $total = (int) ($countResult['total'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $dataQuery =
            "SELECT {$this->select}
             {$fromQuery}
             {$whereQuery}
             ORDER BY {$this->table}.created_at DESC
             LIMIT {$perPage}
             OFFSET {$offset}";

        $this->db->query($dataQuery);

        $this->bindParameters();

        $data = $this->db->resultSet();


        /*
        |--------------------------------------------------------------------------
        | RESULT
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
            'from' => $total > 0
                ? $offset + 1
                : 0,
            'to' => min(
                $offset + $perPage,
                $total
            ),
            'hasNextPage' => $page < $totalPages,
            'hasPreviousPage' => $page > 1,
        ];

        $this->resetQuery();

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | COUNT & EXISTS
    |--------------------------------------------------------------------------
    */


    public function count($column = null, $value = null)
    {
        $this->addSoftDeleteCondition();

        if ($column !== null) {
            $this->where(
                $column,
                $value
            );
        }

        $query =
            "SELECT COUNT(*) AS total
             FROM {$this->table}";

        if (!empty($this->where)) {
            $query .=
                " WHERE " .
                implode(' AND ', $this->where);
        }

        $this->db->query($query);

        $this->bindParameters();

        $result = $this->db->single();

        $this->resetQuery();

        return (int) ($result['total'] ?? 0);
    }


    public function exists($column, $value)
    {
        return $this
            ->where($column, $value)
            ->first() !== false;
    }


    public function countThisMonth(
        $column = 'created_at'
    ) {
        $query =
            "SELECT COUNT(*) AS total
             FROM {$this->table}
             WHERE {$this->table}.{$column}
             >= DATE_FORMAT(
                 CURDATE(),
                 '%Y-%m-01'
             )
             AND {$this->table}.{$column}
             < DATE_FORMAT(
                 CURDATE() + INTERVAL 1 MONTH,
                 '%Y-%m-01'
             )";

        $softDelete =
            $this->getSoftDeleteCondition();

        if ($softDelete) {
            $query .= " AND {$softDelete}";
        }

        $this->db->query($query);

        $result = $this->db->single();

        return (int) ($result['total'] ?? 0);
    }


    public function sumThisMonth(
        $column,
        $dateColumn = 'created_at'
    ) {
        $query =
            "SELECT SUM({$this->table}.{$column}) AS total
             FROM {$this->table}
             WHERE {$this->table}.{$column} IS NOT NULL
             AND {$this->table}.{$dateColumn}
             >= DATE_FORMAT(
                 CURDATE(),
                 '%Y-%m-01'
             )
             AND {$this->table}.{$dateColumn}
             < DATE_FORMAT(
                 CURDATE() + INTERVAL 1 MONTH,
                 '%Y-%m-01'
             )";

        $softDelete =
            $this->getSoftDeleteCondition();

        if ($softDelete) {
            $query .= " AND {$softDelete}";
        }

        $this->db->query($query);

        $result = $this->db->single();

        return (float) ($result['total'] ?? 0);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
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

        $columns =
            implode(', ', array_keys($fields));

        $params =
            ':' .
            implode(', :', array_keys($fields));

        $query =
            "INSERT INTO {$this->table}
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

        $setClause =
            implode(', ', $set);

        $query =
            "UPDATE {$this->table}
             SET {$setClause},
                 updated_at = NOW()
             WHERE id = :id";

        $this->db->query($query);

        foreach ($fields as $key => $value) {
            $this->db->bind($key, $value);
        }

        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */


    /**
     * Hard delete
     */
    public function destroy($id)
    {
        $query =
            "DELETE FROM {$this->table}
             WHERE id = :id";

        $this->db->query($query);

        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /**
     * Soft delete
     */
    public function softDelete($id)
    {
        if (!$this->softDelete) {
            return $this->destroy($id);
        }

        $query =
            "UPDATE {$this->table}
             SET deleted_at = NOW()
             WHERE id = :id
             AND deleted_at IS NULL";

        $this->db->query($query);

        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /**
     * Restore
     */
    public function restore($id)
    {
        if (!$this->softDelete) {
            return 0;
        }

        $query =
            "UPDATE {$this->table}
             SET deleted_at = NULL,
                 updated_at = NOW()
             WHERE id = :id
             AND deleted_at IS NOT NULL";

        $this->db->query($query);

        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */


    protected function bindParameters()
    {
        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }
    }


    protected function resetQuery()
    {
        $this->select = '*';
        $this->joins = [];
        $this->where = [];
        $this->bindings = [];

        // Selalu kembali ke default setelah query selesai
        $this->trashedMode = 'active';
    }
}
