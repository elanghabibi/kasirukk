<?php

class Kategori extends Model
{
    protected $table = "kategori";
    protected $fillable = [
        "nama",
        "slug"
    ];
    protected $softDelete = true;

    public function hasMenu($id)
    {
        $this->db->query("
        SELECT *
        FROM menu
        WHERE kategori_id = :kategori_id
        AND deleted_at IS NULL
    ");

        $this->db->bind(':kategori_id', $id);

        return $this->db->resultSet();
    }
}
